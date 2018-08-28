<template>
<div class="container">
    <div class="row justify-content-sm-between">
        <div class="col-sm-6">
            <div class="input-group mb-4">
                <input type="text" class="form-control" v-model="search" placeholder="关键字">
                <select class="custom-select" @change="category = $event.target.value">
                    <option value="" selected>所有行业</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <select class="custom-select" @change="location = $event.target.value">
                    <option value="" selected>所有地区</option>
                    <option v-for="location in locations" :key="location.en" :value="location.en">{{ location.zh }}</option>
                </select>
            </div>
        </div>
        <div class="col-sm-4 col-md-3 col-lg-2">
            <select class="form-control mb-4" v-model="type">
                <option value="">所有工作类型</option>
                <option value="Part-time">Part-time</option>
                <option value="Full-time">Full-time</option>
                <option value="Intership">Intership</option>
            </select>
        </div>
    </div>

    <div v-if="computedJobs.length" class="row">
        <div v-for="job in computedJobs" :key="job.id" class="col-lg-6 col-xl-4">
            <div class="card mb-5">
                <div class="card-header">
                    <div class="justify-content-between d-flex align-items-center flex-sm-wrap">
                        <h5 class="card-title m-0" :title="job.chinese_title || job.title">{{ job.showTitle }}</h5>
                        
                        <button v-if="job.posted_in_hours < 24" class="btn btn-success btn-sm" data-toggle="modal" data-target="#applyModal" @click="getJob(job)">点选直申
                        <div style="width:15px;height:15px;border-radius:50%;" class="bg-white d-inline-flex justify-content-center align-items-center text-success">&#10004;</div>
                        </button>

                        <button v-else class="btn btn-secondary btn-sm" disabled>点选直申
                        <div style="width:15px;height:15px;border-radius:50%;" class="bg-white d-inline-flex justify-content-center align-items-center text-secondary">&#10004;</div>
                        </button>
                    </div>
                    
                    <div class="text-muted">{{ job.job_type }}</div>
                </div>

                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="card-text">{{ job.excerpt }}</p>
                    
                    <div>
                        <div class="text-truncate" :title="job.company.name">{{ job.company.name }}</div>
                        <p class="small d-flex justify-content-between">
                            <span>地点: <span class="text-secondary">{{ job.company.state }}</span></span>
                            <span>规模: <span class="text-secondary">{{ job.company.scale }}</span></span>
                            <span>2017 H1B: <span class="text-secondary">{{ job.company.totalSponsor }}人</span></span>
                        </p>
                        <div>
                            <span v-for="tag in job.tags" :key="tag.id" class="badge badge-pill badge-secondary">{{ tag.name }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex align-items-center justify-content-between">
                    <small class="text-muted">{{ job.posted_at }}</small>
                    <button title="公司链接" type="submit" class="btn btn-sm p-0 btn-light border-0 icon website" @click="goTo(job.url)"></button>
                    <div>
                        <form class="d-inline" :class="job.is_favorited? 'filled' : ''" :action="'/job/favorite/toggle/'+job.id" method="post" @submit.prevent="toggleFavorite">
                            <button type="submit" class="btn btn-sm p-0 btn-light border-0 icon heart"></button>
                        </form>
                        <span class="favorites">{{ job.favorites_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else v-cloak>抱歉，暂时没有找到您要求的工作，请尝试其他搜索吧。</div>
</div>
</template>

<script>
export default {
    data() {
        return {
            jobs:[],
            search: '',
            category: '',
            location: '',
            type: '',
            offset: 0,
            stopLoading: false,
            categories: [],
            locations: [
                { en: 'NY', zh: '纽约' },
                { en: 'NJ', zh: '新泽西' },
                { en: 'CHICAGO', zh: '芝加哥' },
                { en: 'LOS ANGELES', zh: '洛杉矶' },
                { en: 'MIAMI', zh: '迈阿密' },
                { en: 'BOSTON', zh: '波士顿' },
                { en: 'SAN JOSE', zh: '圣何塞' },
                { en: 'WASHINGTON', zh: '华盛顿' },
                { en: 'ATLANTA', zh: '亚特兰大' },
                { en: 'SAN FRANCISCO', zh: '圣弗朗西斯科' },
                { en: 'SAN DIEGO', zh: '圣地亚哥' }
            ]
        }
    },
    created() {
        const self = this;
        var timeout;
        this.fetch();
        //this.fetchLocations();
        this.fetchCategories();
        $(window).scroll(function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    self.fetch(true);
                }, 60);
            }
        });
    },
    methods: {
        fetch(push = false) {
            if(!push) {
                this.offset = 0;
                this.stopLoading = false;
            }
            if(this.stopLoading) return;
            $.ajax('/job-list', {
                data: {
                    s: this.search,
                    c: this.category,
                    l: this.location,
                    o: this.offset,
                    t: this.type
                },
                context: this,
                success(data) {
                    this.offset += data.length;

                    if(push) this.jobs = this.jobs.concat(data);
                    else this.jobs = data;

                    if(data.length < 9) this.stopLoading = true;
                }
            });
        },
        fetchCategories() {
            if(this.categories = this.tryLocalGet('categories')) return;
            $.ajax('/catagory', {
                context: this,
                success(data) {
                    this.tryLocalSet('categories', data);
                    this.categories = data;
                }
            });
        },
        fetchLocations() {
            if(this.locations = this.tryLocalGet('locations')) return;
            $.ajax('/locations', {
                context: this,
                success(data) {
                    this.tryLocalSet('locations', data);
                    this.locations = data;
                }
            });
        },
        tryLocalGet(item) {
            if(typeof(Storage) !== "undefined") {
                return JSON.parse(localStorage.getItem(item));
            }
        },
        tryLocalSet(item, data) {
            if(typeof(Storage) !== "undefined") {
                return localStorage.setItem(item, JSON.stringify(data));
            }
        },
        toggleFavorite(e) {
            $.post(e.target.getAttribute('action'), [], function() {
                e.target.classList.toggle('filled');
                var span = $(e.target).siblings('.favorites');
                if($(e.target).hasClass('filled')) span.text(parseInt(span.text()) + 1);
                else span.text(parseInt(span.text()) - 1);
            }).fail(function() {
                toastr.error('请先登入');
            });
        },
        getJob(job) {
            window.jobToApply = job;
        },
        goTo(url){
            window.open(url);
        }
    },
    watch: {
        category() {
            this.fetch();
        },
        location() {
            this.fetch();
        },
        search() {
            this.fetch();
        },
        type(){
            this.fetch();
        }
    },
    computed: {
        computedJobs() {
            return this.jobs;
        }
    }
}
</script>
