const app = new Vue({
    el: '#app',
    data: {
        errors: ''
    },
    methods: {
        onSubmit(e) {
            e.preventDefault();
            let uri = e.target.getAttribute('action');
            let fd = new FormData(e.target);
            window.helper.toggle(e.target);
            $.ajax(uri, {
                type: 'post',
                context: this,
                data: fd,
                processData: false,
                contentType: false,
                error(data) {
                    this.errors = JSON.parse(data.responseText).errors;
                },
                success(data) {
                    this.errors = '';
                    toastr.success(data);

                    if(e.target.getAttribute('id') === 'passForm') {
                        e.target.reset();
                    }
                },
                complete: function() {
                    window.helper.toggle(e.target, false);
                }
            });
        },
        onChange(e) {
            let file = e.target.files[0];
            $(e.target).siblings('label').text(file.name);
            $('#resumeFormBtn').click();
        }
    }
});