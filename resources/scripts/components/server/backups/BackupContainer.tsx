import React, { useContext, useEffect, useState } from 'react';
import Spinner from '@/components/elements/Spinner';
import useFlash from '@/plugins/useFlash';
import Can from '@/components/elements/Can';
import CreateBackupButton from '@/components/server/backups/CreateBackupButton';
import FlashMessageRender from '@/components/FlashMessageRender';
import BackupRow from '@/components/server/backups/BackupRow';
import tw from 'twin.macro';
import getServerBackups, { Context as ServerBackupContext } from '@/api/swr/getServerBackups';
import { ServerContext } from '@/state/server';
import ServerContentBlock from '@/components/elements/ServerContentBlock';
import Pagination from '@/components/elements/Pagination';

const BackupContainer = () => {
    const { page, setPage } = useContext(ServerBackupContext);
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const { data: backups, error, isValidating } = getServerBackups();

    const backupLimit = ServerContext.useStoreState((state) => state.server.data!.featureLimits.backups);

    useEffect(() => {
        if (!error) {
            clearFlashes('backups');

            return;
        }

        clearAndAddHttpError({ error, key: 'backups' });
    }, [error]);

    if (!backups || (error && isValidating)) {
        return <Spinner size={'large'} centered />;
    }

    return (
        <ServerContentBlock title={'Резервные копии'}>
            <FlashMessageRender byKey={'backups'} css={tw`mb-4`} />
            <Pagination data={backups} onPageSelect={setPage}>
                {({ items }) =>
                    !items.length ? (
                        // Не показывать сообщения об ошибках, если на сервере нет резервных копий и пользователь не может
                        // создать дополнительные для сервера.
                        !backupLimit ? null : (
                            <p css={tw`text-center text-sm text-neutral-300`}>
                                {page > 1
                                    ? 'Похоже, что у нас закончились резервные копии для показа, попробуйте вернуться на предыдущую страницу.'
                                    : 'Похоже, что на этом сервере нет сохраненных резервных копий.'}
                            </p>
                        )
                    ) : (
                        items.map((backup, index) => (
                            <BackupRow key={backup.uuid} backup={backup} css={index > 0 ? tw`mt-2` : undefined} />
                        ))
                    )
                }
            </Pagination>
            {backupLimit === 0 && (
                <p css={tw`text-center text-sm text-neutral-300`}>
                    Резервные копии не могут быть созданы для этого сервера, так как лимит резервных копий установлен на 0.
                </p>
            )}
            <Can action={'backup.create'}>
                <div css={tw`mt-6 sm:flex items-center justify-end`}>
                    {backupLimit > 0 && backups.backupCount > 0 && (
                        <p css={tw`text-sm text-neutral-300 mb-4 sm:mr-6 sm:mb-0`}>
                            {backups.backupCount} из {backupLimit} резервных копий были созданы для этого сервера.
                        </p>
                    )}
                    {backupLimit > 0 && backupLimit > backups.backupCount && (
                        <CreateBackupButton css={tw`w-full sm:w-auto`} />
                    )}
                </div>
            </Can>
        </ServerContentBlock>
    );
};

export default () => {
    const [page, setPage] = useState<number>(1);
    return (
        <ServerBackupContext.Provider value={{ page, setPage }}>
            <BackupContainer />
        </ServerBackupContext.Provider>
    );
};