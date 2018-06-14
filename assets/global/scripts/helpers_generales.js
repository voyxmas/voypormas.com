let columnsMagic = {
    settings: {
        colMinWidth: 200,
        container: "div#tabla_inscripciones",
        colPos: 1,
        btnNext: ".move.next",
        btnPrev: ".move.prev"
    },
    showHideCols: function() {
        this.settings.rangeOffset = $('tbody > tr:first-child > td.fixed').size();
        this.settings.rangeStart = this.settings.colPos + this.settings.rangeOffset - 1;
        this.settings.rangeStart + this.settings.colShow - 1; 
        // start + end
        let showSelector = $('tbody > tr:not(.fixed) > td,thead > tr:not(.fixed) > th');
        let hideSelector = $('tbody > tr:not(.fixed) > td:not(.fixed):nth-child(n+' + (this.settings.rangeEnd + 1) + '), tbody > tr:not(.fixed) > td:not(.fixed):nth-child(-n+' + (this.settings.rangeStart - 1) + '), thead > tr:not(.fixed) > th:not(.fixed):nth-child(n+' + (this.settings.rangeEnd + 1) + '), thead > tr:not(.fixed) > th:not(.fixed):nth-child(-n+' + (this.settings.rangeStart - 1) + ')');

  
        showSelector.show();
        hideSelector.hide();
        showSelector.addClass('flipInX animated').removeClass('flipOutX');
        hideSelector.addClass('flipOutX animated').removeClass('flipInX');

        this.change();
    },
    showHideButtons: function() {
        if(this.settings.colPos + this.settings.colShow - 1 == this.settings.colCount || this.settings.colCount < this.settings.colShow){
            $(this.settings.btnNext).prop("disabled", true);
        }else{
            $(this.settings.btnNext).prop("disabled", false);
        }
        
        if(this.settings.colPos == 1){
            $(this.settings.btnPrev).prop("disabled", true);
        }else{
            $(this.settings.btnPrev).prop("disabled", false);
        }
    },
    defineColumns: function() {
        let containerWidth = $(this.settings.container).width();
        this.settings.colShow = Math.floor(containerWidth / this.settings.colMinWidth);
        let finalColWidth = Math.floor(containerWidth / this.settings.colShow);
        this.settings.rangeEnd = this.settings.colPos + this.settings.colShow;
        $('tbody > tr > td').width(finalColWidth);
    },
    next: function() {
        if (columnsMagic.settings.colPos + columnsMagic.settings.colShow - 1 < columnsMagic.settings.colCount)
            columnsMagic.settings.colPos++;
        this.showHideCols();
    },
    previous: function() {
        if (columnsMagic.settings.colPos != 1)
            columnsMagic.settings.colPos--;
        this.showHideCols();
    },
    change: function(){
        this.showHideButtons();
        console.log(this.settings);
    },
    init: function() {
        // defaults
        this.defineColumns();
        this.settings.colCount= $(this.settings.container + ' tbody > tr:first-child() td:not(.fixed)').size();
        this.showHideCols(this.settings.colPos, this.settings.colShow);
        this.showHideButtons();
    },
};
$(document).on('click', '.move,#current', function() {
    let move = $(this).data('direction');
    switch (move) {
        case 'next':
            columnsMagic.next();
            break;
        case 'prev':
            columnsMagic.previous();
            break;
    }
});