import React, { useEffect, useState } from 'react';
import { ServerContext } from '@/state/server';
import Modal from '@/components/elements/Modal';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import FlashMessageRender from '@/components/FlashMessageRender';
import useFlash from '@/plugins/useFlash';
import { SocketEvent } from '@/components/server/events';
import { useStoreState } from 'easy-peasy';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faExclamationTriangle } from '@fortawesome/free-solid-svg-icons';

const PIDLimitModalFeature = () => {
    const [visible, setVisible] = useState(false);
    const [loading] = useState(false);

    const status = ServerContext.useStoreState((state) => state.status.value);
    const { clearFlashes } = useFlash();
    const { connected, instance } = ServerContext.useStoreState((state) => state.socket);
    const isAdmin = useStoreState((state) => state.user.data!.rootAdmin);

    useEffect(() => {
        if (!connected || !instance || status === 'running') return;

        const errors = [
            'pthread_create failed',
            'failed to create thread',
            'unable to create thread',
            'unable to create native thread',
            'unable to create new native thread',
            'exception in thread "craft async scheduler management thread"',
        ];

        const listener = (line: string) => {
            if (errors.some((p) => line.toLowerCase().includes(p))) {
                setVisible(true);
            }
        };

        instance.addListener(SocketEvent.CONSOLE_OUTPUT, listener);

        return () => {
            instance.removeListener(SocketEvent.CONSOLE_OUTPUT, listener);
        };
    }, [connected, instance, status]);

    useEffect(() => {
        clearFlashes('feature:pidLimit');
    }, []);

    return (
        <Modal
            visible={visible}
            onDismissed={() => setVisible(false)}
            closeOnBackground={false}
            showSpinnerOverlay={loading}
        >
            <FlashMessageRender key={'feature:pidLimit'} css={tw`mb-4`} />
            {isAdmin ? (
                <>
                    <div css={tw`mt-4 sm:flex items-center`}>
                        <FontAwesomeIcon css={tw`pr-4`} icon={faExclamationTriangle} color={'orange'} size={'4x'} />
                        <h2 css={tw`text-2xl mb-4 text-neutral-100 `}>Достигнут предел памяти или процессов...</h2>
                    </div>
                    <p css={tw`mt-4`}>Этот сервер достиг максимального предела процессов или памяти.</p>
                    <p css={tw`mt-4`}>
                        Увеличение <code css={tw`font-mono bg-neutral-900`}>container_pid_limit</code> в конфигурации wings, <code css={tw`font-mono bg-neutral-900`}>config.yml</code>, может помочь решить эту проблему.
                    </p>
                    <p css={tw`mt-4`}>
                        <b>Примечание: Wings необходимо перезапустить, чтобы изменения в конфигурационном файле вступили в силу</b>
                    </p>
                    <div css={tw`mt-8 sm:flex items-center justify-end`}>
                        <Button onClick={() => setVisible(false)} css={tw`w-full sm:w-auto border-transparent`}>
                            Закрыть
                        </Button>
                    </div>
                </>
            ) : (
                <>
                    <div css={tw`mt-4 sm:flex items-center`}>
                        <FontAwesomeIcon css={tw`pr-4`} icon={faExclamationTriangle} color={'orange'} size={'4x'} />
                        <h2 css={tw`text-2xl mb-4 text-neutral-100`}>Возможно, достигнут предел ресурсов...</h2>
                    </div>
                    <p css={tw`mt-4`}>
                        Этот сервер пытается использовать больше ресурсов, чем выделено. Пожалуйста, свяжитесь с администратором и передайте ему следующую ошибку.
                    </p>
                    <p css={tw`mt-4`}>
                        <code css={tw`font-mono bg-neutral-900`}>
                            pthread_create failed, Возможно, закончилась память или достигнуты пределы процессов/ресурсов
                        </code>
                    </p>
                    <div css={tw`mt-8 sm:flex items-center justify-end`}>
                        <Button onClick={() => setVisible(false)} css={tw`w-full sm:w-auto border-transparent`}>
                            Закрыть
                        </Button>
                    </div>
                </>
            )}
        </Modal>
    );
};

export default PIDLimitModalFeature;