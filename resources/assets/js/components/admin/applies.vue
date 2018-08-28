<template>
    <table class="table table-striped table-sm">
        <caption>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="filter" v-model="filter" id="inlineRadio1" value="0">
                <label class="form-check-label" for="inlineRadio1">新申请</label>
            </div>
            <!-- <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="filter" v-model="filter" id="inlineRadio2" value="all">
                <label class="form-check-label" for="inlineRadio2">全部申请</label>
            </div> -->
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="filter" v-model="filter" id="inlineRadio3" value="1">
                <label class="form-check-label" for="inlineRadio3">发过的申请</label>
            </div>

            <button @click="sendEmail" type="button" class="btn btn-primary float-right">发送HR邮件</button>
        </caption>
        <thead>
            <tr>
                <th>学生</th><th>公司</th><th>工作</th><th>申请日期</th><th></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="apply in filteredApplies" :key="apply.id">
                <td v-text="apply.user.name"></td>
                <td v-text="apply.post.company.name"></td>
                <td v-text="apply.post.showTitle"></td>
                <td v-text="apply.created_at"></td>
                <td>
                    <a :href="'/dashboard/resume/download?r='+apply.user.resume">简历</a>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    data() {
        return {
            applies:[],
            filter: 0
        }
    },
    created() {
        this.fetch();
    },
    computed: {
        filteredApplies() {
            return this.applies.filter(function(val) {
                return val.is_applied == this.filter;
            }.bind(this));
        }
    },
    methods: {
        fetch() {
            const self = this;
            $.ajax('/admin/fetch/applies', {
                success: function(data) {
                    self.applies = data;
                }
            });
        },
        sendEmail(e) {
            if(confirm('确定开始群发吗？')) {
                e.target.classList.add('loading');
                $.post('/admin/send/applies', function() {
                   // location.reload();
                    //e.target.classList.remove('loading');
                });
            }
        }
    }
}
</script>
