{if $act neq 'add'}
    <script>
        function sendAutoSaveRequest($element, hardValue) {
            var data = {
                entity_id: typeof($element.data('entity-id')) != 'undefined' ? $element.data('entity-id') : getEntityID(),
                field: {
                    entity: typeof($element.data('entity')) != 'undefined' ? $element.data('entity') : mod,
                    name: typeof($element.data('field')) != 'undefined' ? $element.data('field') : $element.attr('name'),
                    value: typeof(hardValue) != 'undefined' ? hardValue : $element.val(),
                    add_opt: typeof($element.data('add-opt')) != 'undefined' ? $element.data('add-opt') : ''
                }
            };

            $.rex(mod, 'fieldAutoSave', data, function (response) {
                if (response === 'success') {

                } else {
                    $element.val($element.data('prev-val'));
                }
            });
        }

        function sendRelatedAutoSave($option, primaryField, action) {
            var selectEl        = $option.parent(),
                primaryField    = primaryField && typeof(primaryField) != 'undefined' ? primaryField : $('[name="product_id"]'),
                primaryFieldVal = primaryField.length ? primaryField.val() : false,
                action          = typeof(action) != 'undefined' ? action : 'add';

            if (!primaryFieldVal) {
                return false;
            }

            var data = {
                entity_id: primaryFieldVal,
                related_id: selectEl.data('related-id'),
                action: action,
                field: {
                    entity: selectEl.data('entity'),
                    name: 'product_id',
                    related_name: selectEl.data('related-field'),
                    changed_name: selectEl.data('changed-name'),
                    value: $option.val()
                }
            };

            $.rex(mod, 'fieldAutoSave', data);
        }

        function getEntityID() {
            var entityIdEl = $('[name="entity[exist_id]"]');
            return entityIdEl.length && entityIdEl.val() ? entityIdEl.val() : false;
        }

        $(document).ready(function () {
            var mod = $('input[name="mod"]').val();
            $(document).on('focusout', '[data-autosave="true"]', function () {
                var $this = $(this),
                    inputValue = $this.val();

                if ($this.is('[type="checkbox"]')) {
                    if (typeof($this.data('checked')) != 'undefined' && typeof($this.data('unchecked')) != 'undefined') {
                        inputValue = $this.prop('checked') ? $this.data('checked') : $this.data('unchecked')
                    } else {
                        inputValue = $this.prop('checked') ? 1 : 0
                    }
                }

                sendAutoSaveRequest($this, inputValue);
            }).on('click', 'input[type="checkbox"][data-autosave="true"]', function () {
                $(this).attr('prev-val', $(this).val());
                if ($(this).attr('name') == 'entity[active]') {
                    if ($(this).attr("checked") != 'checked') {
                        $(this).siblings('span').text('Не активен');
                    } else {
                        $(this).siblings('span').text('Активен');
                    }
                }
                $(this).focusout();
            }).on('change', 'select[data-autosave="true"]', function() {
                $(this).focusout();
            });
        });
    </script>
{/if}