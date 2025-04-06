<?php

return [
    'validation' => [
        'fqdn_not_resolvable' => 'Предоставленный FQDN или IP адрес не разрешается в действительный IP адрес.',
        'fqdn_required_for_ssl' => 'Полное доменное имя, которое разрешается в публичный IP адрес, необходимо для использования SSL для этого узла.',
    ],
    'notices' => [
        'allocations_added' => 'Выделения успешно добавлены к этому узлу.',
        'node_deleted' => 'Узел успешно удален из панели.',
        'location_required' => 'Вы должны иметь хотя бы одну локацию, прежде чем добавлять узел в эту панель.',
        'node_created' => 'Новый узел успешно создан. Вы можете автоматически настроить демон на этой машине, посетив вкладку \'Конфигурация\'. <strong>Прежде чем добавлять серверы, вы должны сначала выделить хотя бы один IP адрес и порт.</strong>',
        'node_updated' => 'Информация об узле обновлена. Если какие-либо настройки демона были изменены, вам нужно перезагрузить его, чтобы эти изменения вступили в силу.',
        'unallocated_deleted' => 'Удалены все нераспределенные порты для <code>:ip</code>.',
    ],
];
