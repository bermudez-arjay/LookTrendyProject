import './bootstrap';
import Chart from 'chart.js/auto';
window.Chart = Chart;
import jquery from 'jquery';
window.$ = window.jQuery = jquery;

import 'select2/dist/js/select2.min';
import 'select2/dist/css/select2.min.css';
document.addEventListener('livewire:init', () => {
   
    Livewire.hook('element.initialized', (el) => {
        $(el).find('.select2-enhanced').select2({
            width: '100%',
            dropdownAutoWidth: true,
            minimumResultsForSearch: 10,
            dropdownParent: $(el).closest('.modal') || $(document.body)
        }).on('change', function() {
            const field = $(this).attr('wire:model');
            const value = $(this).val();
            Livewire.dispatch('select2-changed', {field, value});
        });
    });
});