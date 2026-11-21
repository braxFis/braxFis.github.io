(function (wp) {
    if (!wp || !wp.blocks || !wp.element || !wp.components || !wp.blockEditor) return;

    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var useEffect = wp.element.useEffect;
    var registerBlockType = wp.blocks.registerBlockType;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var PanelBody = wp.components.PanelBody;
    var SelectControl = wp.components.SelectControl;
    var TextControl = wp.components.TextControl;
    var TextareaControl = wp.components.TextareaControl;
    var defaults = (window.GDESReviewScore && Array.isArray(window.GDESReviewScore.criteria))
        ? window.GDESReviewScore.criteria
        : ['Gameplay och kontroller', 'Speldesign och innehåll', 'Grafik och design', 'Ljud och musik', 'Teknisk kvalitet'];

    registerBlockType('gamedefiners/review-score', {
        edit: function (props) {
            var criteria = Array.isArray(props.attributes.criteria) ? props.attributes.criteria.slice(0, 5) : [];
            var hasSaved = criteria.length === 5;
            useEffect(function () {
                if (!hasSaved) {
                    props.setAttributes({ criteria: defaults.slice(0, 5).map(function (label) {
                        return { label: label, status: 'unrated', note: '' };
                    }) });
                }
            }, [hasSaved]);
            if (!hasSaved) {
                criteria = defaults.slice(0, 5).map(function (label) {
                    return { label: label, status: 'unrated', note: '' };
                });
            }
            var score = criteria.filter(function (item) { return item.status === 'meets'; }).length;
            var assessed = criteria.filter(function (item) { return item.status === 'meets' || item.status === 'does_not_meet'; }).length;

            function updateItem(index, key, value) {
                var updated = criteria.map(function (item) { return Object.assign({}, item); });
                updated[index][key] = value;
                props.setAttributes({ criteria: updated });
            }

            return el(Fragment, {},
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Bedömning', initialOpen: true },
                        el('p', {}, 'Samma fem kriterier används i alla recensioner. Ändra standardnamnen centralt under Editorial Studio → Inställningar.'))
                ),
                el('div', { className: props.className + ' gdes-review-editor' },
                    el('div', { className: 'gdes-review-editor__top' },
                        el('div', {}, el('strong', {}, 'Vår bedömning'), el('p', {}, 'Välj ett utfall och skriv en kort motivering för varje kriterium.')),
                        el('div', { className: 'gdes-review-editor__score' }, el('strong', {}, score + '/5'), el('span', {}, assessed + '/5 bedömda'))
                    ),
                    criteria.map(function (item, index) {
                        return el('div', { className: 'gdes-review-editor__item', key: 'criterion-' + index },
                            el(TextControl, {
                                label: 'Kriterium ' + (index + 1),
                                value: item.label || defaults[index] || '',
                                onChange: function (value) { updateItem(index, 'label', value); },
                                disabled: true
                            }),
                            el(SelectControl, {
                                label: 'Bedömning',
                                value: item.status || 'unrated',
                                options: [
                                    { label: 'Ej bedömt ännu', value: 'unrated' },
                                    { label: 'Uppfyller (1 poäng)', value: 'meets' },
                                    { label: 'Uppfyller inte (0 poäng)', value: 'does_not_meet' }
                                ],
                                onChange: function (value) { updateItem(index, 'status', value); }
                            }),
                            el(TextareaControl, {
                                label: 'Motivering',
                                value: item.note || '',
                                rows: 2,
                                onChange: function (value) { updateItem(index, 'note', value); }
                            })
                        );
                    }),
                    el('p', { className: 'gdes-review-editor__help' }, 'Poängen räknas automatiskt. ”Ej bedömt ännu” räknas inte som uppfyllt. Slutbetyget bestäms separat av redaktionen.')
                )
            );
        },
        save: function () {
            return null;
        }
    });
}(window.wp));
