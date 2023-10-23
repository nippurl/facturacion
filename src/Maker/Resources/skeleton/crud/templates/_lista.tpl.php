{#  @var <?="$entity_twig_var_plural $entity_class_name_long".'[]'?> #}
<table class="table">
    <thead>
    <tr>
        <?php foreach ($entity_fields as $field): ?>
            <?php if (! in_array($field['fieldName'] ,array('CreateAt','UpdateAt', 'CreateBy', 'UpdateBy'))) : ?>
                <th><?= ucfirst($field['fieldName']) ?></th>
            <?php endif ?>
        <?php endforeach; ?>
        <th>actions</th>
    </tr>
    </thead>
    <tbody>
    {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
    <tr>
        <?php foreach ($entity_fields as $field): ?>
            <?php if (! in_array($field['fieldName'] ,array('CreateAt','UpdateAt', 'CreateBy', 'UpdateBy'))) : ?>
                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
            <?php endif ?>
        <?php endforeach; ?>
        <td>
            <a href="{{ path('<?= $route_name ?>_show', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}" class="show">Ver</a>
            <a href="{{ path('<?= $route_name ?>_edit', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}" class="edit">Editar</a>
        </td>
    </tr>
    {% else %}
    <tr>
        <td colspan="<?= (count($entity_fields) + 1) ?>">No hay registros</td>
    </tr>
    {% endfor %}
    </tbody>
</table>