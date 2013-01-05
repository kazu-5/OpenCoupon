/**
 * @see http://103px.blog.fc2.com/blog-entry-40.html
 */
jQuery(function($){
    $.datetimepicker.regional['ja'] = {
        clearText: '',
        clearStatus: '',
        closeText: '×',
        closeStatus: '',
        prevText: '&nbsp;←前月', prevStatus: '',
        nextText: '次月→&nbsp;', nextStatus: '',
        currentText: '今日', currentStatus: '',
        monthNames: ['1月','2月','3月','4月','5月','6月',
        '7月','8月','9月','10月','11月','12月'],
        monthNamesShort: ['1月','2月','3月','4月','5月','6月',
        '7月','8月','9月','10月','11月','12月'],
        monthStatus: '', yearStatus: '',
        weekHeader: 'Wk', weekStatus: '',
        dayNames: ['日','月','火','水','木','金','土'],
        dayNamesShort: ['日','月','火','水','木','金','土'],
        dayNamesMin: ['日','月','火','水','木','金','土'],
        dayStatus: 'DD', dateStatus: 'D, M d',
        dateFormat: 'yyyy-mm-dd HH:MM:00', firstDay: 0,
        initStatus: '',
		yearRange:'-80:+10',
        isRTL: false
    };
    $.datetimepicker.setDefaults($.datetimepicker.regional['ja']);
	//必要があればコメントアウトをはずしてオプションを設定してください
    /*
    $.datetimepicker.setDefaults({
		showButtonPanel:true,
        changeMonth: true,
        changeYear: true,
        showOn: 'button',
        buttonImageOnly: true,
        buttonImage: '',
        buttonText: 'Calendar',
        showAnim: 'slideDown',
        speed: 'fast'
    });
    */
});