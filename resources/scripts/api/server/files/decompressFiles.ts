import http from '@/api/http';

export default async (uuid: string, directory: string, file: string): Promise<void> => {
    await http.post(
        `/api/client/servers/${uuid}/files/decompress`,
        { root: directory, file },
        {
            timeout: 300000,
            timeoutErrorMessage:
                'Кажется, этот архив занимает много времени для разархивирования. После завершения разархивирование файлы появятся.',
        }
    );
};
