<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Ramsey\Uuid\Uuid;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Pterodactyl\Models\Nest;
use Illuminate\Http\Response;
use Pterodactyl\Models\Mount;
use Pterodactyl\Models\Location;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\MountFormRequest;
use Pterodactyl\Repositories\Eloquent\MountRepository;
use Pterodactyl\Contracts\Repository\NestRepositoryInterface;
use Pterodactyl\Contracts\Repository\LocationRepositoryInterface;

class MountController extends Controller
{
    /**
     * Конструктор MountController.
     */
    public function __construct(
        protected AlertsMessageBag $alert,
        protected NestRepositoryInterface $nestRepository,
        protected LocationRepositoryInterface $locationRepository,
        protected MountRepository $repository,
        protected ViewFactory $view
    ) {
    }

    /**
     * Возвращает страницу обзора монтирования.
     */
    public function index(): View
    {
        return $this->view->make('admin.mounts.index', [
            'mounts' => $this->repository->getAllWithDetails(),
        ]);
    }

    /**
     * Возвращает страницу просмотра монтирования.
     *
     * @throws \Pterodactyl\Exceptions\Repository\RecordNotFoundException
     */
    public function view(string $id): View
    {
        $nests = Nest::query()->with('eggs')->get();
        $locations = Location::query()->with('nodes')->get();

        return $this->view->make('admin.mounts.view', [
            'mount' => $this->repository->getWithRelations($id),
            'nests' => $nests,
            'locations' => $locations,
        ]);
    }

    /**
     * Обрабатывает запрос на создание нового монтирования.
     *
     * @throws \Throwable
     */
    public function create(MountFormRequest $request): RedirectResponse
    {
        $model = (new Mount())->fill($request->validated());
        $model->forceFill(['uuid' => Uuid::uuid4()->toString()]);

        $model->saveOrFail();
        $mount = $model->fresh();

        $this->alert->success('Монтирование было успешно создано.')->flash();

        return redirect()->route('admin.mounts.view', $mount->id);
    }

    /**
     * Обрабатывает запрос на обновление или удаление местоположения.
     *
     * @throws \Throwable
     */
    public function update(MountFormRequest $request, Mount $mount): RedirectResponse
    {
        if ($request->input('action') === 'delete') {
            return $this->delete($mount);
        }

        $mount->forceFill($request->validated())->save();

        $this->alert->success('Монтирование было успешно обновлено.')->flash();

        return redirect()->route('admin.mounts.view', $mount->id);
    }

    /**
     * Удаляет местоположение из системы.
     *
     * @throws \Exception
     */
    public function delete(Mount $mount): RedirectResponse
    {
        $mount->delete();

        return redirect()->route('admin.mounts');
    }

    /**
     * Добавляет яйца к многим отношениям монтирования.
     */
    public function addEggs(Request $request, Mount $mount): RedirectResponse
    {
        $validatedData = $request->validate([
            'eggs' => 'required|exists:eggs,id',
        ]);

        $eggs = $validatedData['eggs'] ?? [];
        if (count($eggs) > 0) {
            $mount->eggs()->attach($eggs);
        }

        $this->alert->success('Монтирование было успешно обновлено.')->flash();

        return redirect()->route('admin.mounts.view', $mount->id);
    }

    /**
     * Добавляет узлы к многим отношениям монтирования.
     */
    public function addNodes(Request $request, Mount $mount): RedirectResponse
    {
        $data = $request->validate(['nodes' => 'required|exists:nodes,id']);

        $nodes = $data['nodes'] ?? [];
        if (count($nodes) > 0) {
            $mount->nodes()->attach($nodes);
        }

        $this->alert->success('Монтирование было успешно обновлено.')->flash();

        return redirect()->route('admin.mounts.view', $mount->id);
    }

    /**
     * Удаляет яйцо из многим отношений монтирования.
     */
    public function deleteEgg(Mount $mount, int $egg_id): Response
    {
        $mount->eggs()->detach($egg_id);

        return response('', 204);
    }

    /**
     * Удаляет узел из многим отношений монтирования.
     */
    public function deleteNode(Mount $mount, int $node_id): Response
    {
        $mount->nodes()->detach($node_id);

        return response('', 204);
    }
}