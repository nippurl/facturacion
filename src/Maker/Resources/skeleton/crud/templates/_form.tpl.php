{# @var form \App\Form\<?=  $entity_class_name ?>Type #}
{{ form_start(form) }}
    {{ form_widget(form) }}
    <button class="btn btn-success">{{ button_label|default('Guardar') }}</button>
{{ form_end(form) }}
