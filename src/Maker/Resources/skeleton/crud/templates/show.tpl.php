{#  @var <?="$entity_class_name $entity_class_name_long"?> #}
<?= $helper->getHeadPrintCode( '{{'. $entity_class_name .' }}') ?>

{% block body %}
    <h1> <?= '{{'.$entity_class_name.' }}' ?>   </h1>

    <table class="table">
        <tbody>
<?php foreach ($entity_fields as $field): ?>
    <?php if (! in_array($field['fieldName'] ,array('createAt','updateAt', 'createBy', 'updateBy'))) : ?>
            <tr>
                <th><?= ucfirst($field['fieldName']) ?></th>
                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
            </tr>
    <?php endif ?>
<?php endforeach; ?>
        </tbody>
    </table>

    <a href="{{ path('<?= $route_name ?>_index') }}" class="exit">Volver</a>

    <a href="{{ path('<?= $route_name ?>_edit', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}" class="edit">Editar</a>

    {{ include('<?= $templates_path ?>/_delete_form.html.twig') }}
{% endblock %}
