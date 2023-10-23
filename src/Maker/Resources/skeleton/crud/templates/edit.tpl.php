{# @var form \App\Form\<?=  $entity_class_name ?>Type #}
{#  @var <?="$entity_class_name $entity_class_name_long"?> #}

<?= $helper->getHeadPrintCode('Edit '.$entity_class_name) ?>

{% block body %}
    <h1>Edit <?= $entity_class_name ?></h1>

    {{ include('<?= $templates_path ?>/_form.html.twig', {'button_label': 'Guardar'}) }}

    <a href="{{ path('<?= $route_name ?>_index') }}" class="exit">Volver</a>

    {{ include('<?= $templates_path ?>/_delete_form.html.twig') }}
{% endblock %}
