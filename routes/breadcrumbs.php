<?php

Breadcrumbs::register('index', function ($breadcrumbs) {
    $breadcrumbs->push('Главная', route('index'));
});

Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Дом', route('home'));
});

Breadcrumbs::register('employees.index', function ($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Сотрудники', route('employees.index'));
});

Breadcrumbs::register('employees.create', function ($breadcrumbs) {
    $breadcrumbs->parent('employees.index');
    $breadcrumbs->push('Создание сотрудника', route('employees.create'));
});

Breadcrumbs::register('employees.show', function ($breadcrumbs, $employee) {
    $breadcrumbs->parent('employees.index');
    $breadcrumbs->push($employee->full_name, route('employees.show', $employee));
});

Breadcrumbs::register('employees.edit', function ($breadcrumbs, $employee) {
    $breadcrumbs->parent('employees.show', $employee);
    $breadcrumbs->push('Редактирование пользователя', route('employees.edit', $employee));
});

Breadcrumbs::register('contacts.index', function ($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Контакты', route('contacts.index'));
});
Breadcrumbs::register('dictionary.index', function ($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Словари', route('dictionary.index'));
});
