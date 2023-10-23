{#  @var <?="$entity_class_name $entity_class_name_long"?> #}
{# @var form \App\Form\<?=  $entity_class_name ?>Type #}
<?= $helper->getHeadPrintCode('Nuevo '.$entity_class_name) ?>

{% block body %}
    <h1>Nuevo <?= $entity_class_name ?></h1>

    {{ include('<?= $templates_path ?>/_form.html.twig') }}

    <a href="{{ path('<?= $route_name ?>_index') }}" class="exit">Volver</a>
{% endblock %}
