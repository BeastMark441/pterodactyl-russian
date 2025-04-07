<?php

namespace Pterodactyl\Models;

use Illuminate\Support\Collection;

class Permission extends Model
{
    /**
     * Имя ресурса для этой модели, когда она преобразуется в
     * API-представление с использованием fractal.
     */
    public const RESOURCE_NAME = 'subuser_permission';

    /**
     * Константы, определяющие различные доступные разрешения.
     */
    public const ACTION_WEBSOCKET_CONNECT = 'websocket.connect';
    public const ACTION_CONTROL_CONSOLE = 'control.console';
    public const ACTION_CONTROL_START = 'control.start';
    public const ACTION_CONTROL_STOP = 'control.stop';
    public const ACTION_CONTROL_RESTART = 'control.restart';

    public const ACTION_DATABASE_READ = 'database.read';
    public const ACTION_DATABASE_CREATE = 'database.create';
    public const ACTION_DATABASE_UPDATE = 'database.update';
    public const ACTION_DATABASE_DELETE = 'database.delete';
    public const ACTION_DATABASE_VIEW_PASSWORD = 'database.view_password';

    public const ACTION_SCHEDULE_READ = 'schedule.read';
    public const ACTION_SCHEDULE_CREATE = 'schedule.create';
    public const ACTION_SCHEDULE_UPDATE = 'schedule.update';
    public const ACTION_SCHEDULE_DELETE = 'schedule.delete';

    public const ACTION_USER_READ = 'user.read';
    public const ACTION_USER_CREATE = 'user.create';
    public const ACTION_USER_UPDATE = 'user.update';
    public const ACTION_USER_DELETE = 'user.delete';

    public const ACTION_BACKUP_READ = 'backup.read';
    public const ACTION_BACKUP_CREATE = 'backup.create';
    public const ACTION_BACKUP_DELETE = 'backup.delete';
    public const ACTION_BACKUP_DOWNLOAD = 'backup.download';
    public const ACTION_BACKUP_RESTORE = 'backup.restore';

    public const ACTION_ALLOCATION_READ = 'allocation.read';
    public const ACTION_ALLOCATION_CREATE = 'allocation.create';
    public const ACTION_ALLOCATION_UPDATE = 'allocation.update';
    public const ACTION_ALLOCATION_DELETE = 'allocation.delete';

    public const ACTION_FILE_READ = 'file.read';
    public const ACTION_FILE_READ_CONTENT = 'file.read-content';
    public const ACTION_FILE_CREATE = 'file.create';
    public const ACTION_FILE_UPDATE = 'file.update';
    public const ACTION_FILE_DELETE = 'file.delete';
    public const ACTION_FILE_ARCHIVE = 'file.archive';
    public const ACTION_FILE_SFTP = 'file.sftp';

    public const ACTION_STARTUP_READ = 'startup.read';
    public const ACTION_STARTUP_UPDATE = 'startup.update';
    public const ACTION_STARTUP_DOCKER_IMAGE = 'startup.docker-image';

    public const ACTION_SETTINGS_RENAME = 'settings.rename';
    public const ACTION_SETTINGS_REINSTALL = 'settings.reinstall';

    public const ACTION_ACTIVITY_READ = 'activity.read';

    /**
     * Следует ли использовать метки времени для этой модели.
     */
    public $timestamps = false;

    /**
     * Таблица, связанная с моделью.
     */
    protected $table = 'permissions';

    /**
     * Поля, которые не подлежат массовому присвоению.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Приведение значений к правильному типу.
     */
    protected $casts = [
        'subuser_id' => 'integer',
    ];

    public static array $validationRules = [
        'subuser_id' => 'required|numeric|min:1',
        'permission' => 'required|string',
    ];

    /**
     * Все разрешения, доступные в системе. Вы должны использовать self::permissions()
     * для их получения, а не обращаться напрямую к этому массиву, так как он может измениться.
     *
     * @see \Pterodactyl\Models\Permission::permissions()
     */
    protected static array $permissions = [
        'websocket' => [
            'description' => 'Позволяет пользователю подключаться к веб-сокету сервера, предоставляя доступ к просмотру вывода консоли и статистики сервера в реальном времени.',
            'keys' => [
                'connect' => 'Позволяет пользователю подключаться к экземпляру веб-сокета сервера для потоковой передачи консоли.',
            ],
        ],

        'control' => [
            'description' => 'Разрешения, которые контролируют возможность пользователя управлять состоянием сервера или отправлять команды.',
            'keys' => [
                'console' => 'Позволяет пользователю отправлять команды на экземпляр сервера через консоль.',
                'start' => 'Позволяет пользователю запускать сервер, если он остановлен.',
                'stop' => 'Позволяет пользователю останавливать сервер, если он работает.',
                'restart' => 'Позволяет пользователю выполнять перезапуск сервера. Это позволяет им запускать сервер, если он отключен, но не переводить сервер в полностью остановленное состояние.',
            ],
        ],

        'user' => [
            'description' => 'Разрешения, которые позволяют пользователю управлять другими субпользователями на сервере. Они никогда не смогут редактировать свою учетную запись или назначать разрешения, которых у них самих нет.',
            'keys' => [
                'create' => 'Позволяет пользователю создавать новых субпользователей для сервера.',
                'read' => 'Позволяет пользователю просматривать субпользователей и их разрешения для сервера.',
                'update' => 'Позволяет пользователю изменять других субпользователей.',
                'delete' => 'Позволяет пользователю удалять субпользователя с сервера.',
            ],
        ],

        'file' => [
            'description' => 'Разрешения, которые контролируют возможность пользователя изменять файловую систему этого сервера.',
            'keys' => [
                'create' => 'Позволяет пользователю создавать дополнительные файлы и папки через Панель или прямую загрузку.',
                'read' => 'Позволяет пользователю просматривать содержимое каталога, но не просматривать содержимое файлов или скачивать их.',
                'read-content' => 'Позволяет пользователю просматривать содержимое данного файла. Это также позволит пользователю скачивать файлы.',
                'update' => 'Позволяет пользователю обновлять содержимое существующего файла или каталога.',
                'delete' => 'Позволяет пользователю удалять файлы или каталоги.',
                'archive' => 'Позволяет пользователю архивировать содержимое каталога, а также распаковывать существующие архивы в системе.',
                'sftp' => 'Позволяет пользователю подключаться к SFTP и управлять файлами сервера, используя другие назначенные разрешения для файлов.',
            ],
        ],

        'backup' => [
            'description' => 'Разрешения, которые контролируют возможность пользователя создавать и управлять резервными копиями сервера.',
            'keys' => [
                'create' => 'Позволяет пользователю создавать новые резервные копии для этого сервера.',
                'read' => 'Позволяет пользователю просматривать все резервные копии, существующие для этого сервера.',
                'delete' => 'Позволяет пользователю удалять резервные копии из системы.',
                'download' => 'Позволяет пользователю скачивать резервную копию сервера. Опасность: это дает пользователю доступ ко всем файлам сервера в резервной копии.',
                'restore' => 'Позволяет пользователю восстанавливать резервную копию сервера. Опасность: это позволяет пользователю удалить все файлы сервера в процессе.',
            ],
        ],

        // Управляет разрешениями для редактирования или просмотра распределений сервера.
        'allocation' => [
            'description' => 'Разрешения, которые контролируют возможность пользователя изменять распределение портов для этого сервера.',
            'keys' => [
                'read' => 'Позволяет пользователю просматривать все распределения, в настоящее время назначенные этому серверу. Пользователи с любым уровнем доступа к этому серверу всегда могут видеть основное распределение.',
                'create' => 'Позволяет пользователю назначать дополнительные распределения серверу.',
                'update' => 'Позволяет пользователю изменять основное распределение сервера и прикреплять примечания к каждому распределению.',
                'delete' => 'Позволяет пользователю удалять распределение с сервера.',
            ],
        ],

        // Управляет разрешениями для редактирования или просмотра параметров запуска сервера.
        'startup' => [
            'description' => 'Разрешения, которые контролируют возможность пользователя просматривать параметры запуска этого сервера.',
            'keys' => [
                'read' => 'Позволяет пользователю просматривать переменные запуска сервера.',
                'update' => 'Позволяет пользователю изменять переменные запуска сервера.',
                'docker-image' => 'Позволяет пользователю изменять образ Docker, используемый при запуске сервера.',
            ],
        ],

        'database' => [
            'description' => 'Разрешения, которые контролируют доступ пользователя к управлению базами данных для этого сервера.',
            'keys' => [
                'create' => 'Позволяет пользователю создавать новую базу данных для этого сервера.',
                'read' => 'Позволяет пользователю просматривать базу данных, связанную с этим сервером.',
                'update' => 'Позволяет пользователю менять пароль экземпляра базы данных. Если у пользователя нет разрешения view_password, он не увидит обновленный пароль.',
                'delete' => 'Позволяет пользователю удалять экземпляр базы данных с этого сервера.',
                'view_password' => 'Позволяет пользователю просматривать пароль, связанный с экземпляром базы данных для этого сервера.',
            ],
        ],

        'schedule' => [
            'description' => 'Разрешения, которые контролируют доступ пользователя к управлению расписанием для этого сервера.',
            'keys' => [
                'create' => 'Позволяет пользователю создавать новые расписания для этого сервера.', // task.create-schedule
                'read' => 'Позволяет пользователю просматривать расписания и связанные с ними задачи для этого сервера.', // task.view-schedule, task.list-schedules
                'update' => 'Позволяет пользователю обновлять расписания и задачи расписания для этого сервера.', // task.edit-schedule, task.queue-schedule, task.toggle-schedule
                'delete' => 'Позволяет пользователю удалять расписания для этого сервера.', // task.delete-schedule
            ],
        ],

        'settings' => [
            'description' => 'Разрешения, которые контролируют доступ пользователя к настройкам этого сервера.',
            'keys' => [
                'rename' => 'Позволяет пользователю переименовывать этот сервер и изменять его описание.',
                'reinstall' => 'Позволяет пользователю инициировать переустановку этого сервера.',
            ],
        ],

        'activity' => [
            'description' => 'Разрешения, которые контролируют доступ пользователя к журналам активности сервера.',
            'keys' => [
                'read' => 'Позволяет пользователю просматривать журналы активности сервера.',
            ],
        ],
    ];

    /**
     * Возвращает все разрешения, доступные в системе для пользователя,
     * при управлении сервером.
     */
    public static function permissions(): Collection
    {
        return Collection::make(self::$permissions);
    }
}
