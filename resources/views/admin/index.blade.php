@extends('layouts.admin')

@section('title')
    Администрирование
@endsection

@section('content-header')
    <h1>Обзор администрирования<small>Краткий обзор вашей системы.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li class="active">Обзор</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box
            @if($version->isLatestPanel())
                box-success
            @else
                box-danger
            @endif
        ">
            <div class="box-header with-border">
                <h3 class="box-title">Информация о системе</h3>
            </div>
            <div class="box-body">
                @if ($version->isLatestPanel())
                    Вы используете Pterodactyl Panel версии <code>{{ config('app.version') }}</code>. Ваша панель актуальна!
                @else
                    Ваша панель <strong>не актуальна!</strong> Последняя версия <a href="https://github.com/Pterodactyl/Panel/releases/v{{ $version->getPanel() }}" target="_blank"><code>{{ $version->getPanel() }}</code></a>, а вы используете версию <code>{{ config('app.version') }}</code>.
                @endif
                
                <hr>
                
                <h4>Информация о русском переводе</h4>
                <p>Версия перевода: <code>1.11.10</code>
                <a href="https://github.com/BeastMark441/pterodactyl-russian" target="_blank" class="btn btn-xs btn-primary">
                    <i class="fa fa-fw fa-github"></i> Репозиторий перевода
                </a></p>
                <p class="text-muted" style="margin-top: 10px;">Нашли баг или есть предложения по улучшению? <a href="https://github.com/BeastMark441/pterodactyl-russian/issues" target="_blank">Свяжитесь со мной</a>, <a href="https://vk.com/defaltmark" target="_blank">VK</a>, <a href="https://t.me/BeastMarkWork" target="_blank">Telegram</a>.</p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="{{ $version->getDiscord() }}"><button class="btn btn-warning" style="width:100%;"><i class="fa fa-fw fa-support"></i> Получить помощь <small>(через Discord)</small></button></a>
    </div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="https://pterodactyl.io"><button class="btn btn-primary" style="width:100%;"><i class="fa fa-fw fa-link"></i> Документация</button></a>
    </div>
    <div class="clearfix visible-xs-block">&nbsp;</div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="https://github.com/pterodactyl/panel"><button class="btn btn-primary" style="width:100%;"><i class="fa fa-fw fa-support"></i> Github</button></a>
    </div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="{{ $version->getDonations() }}"><button class="btn btn-success" style="width:100%;"><i class="fa fa-fw fa-money"></i> Поддержать проект</button></a>
    </div>
</div>
@endsection