<template>
    <div>
        <nav v-if="data.last_page !== 1" aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item cursor-pointer" @click="gotoPage(1)" v-if="data.current_page != 1">
                    <a class="page-link">First</a>
                </li>
                <li class="page-item cursor-pointer" @click="gotoPreviousPage()" v-if="data.prev_page_url">
                    <a class="page-link">&#9001;</a>
                </li>

                <li class="page-item cursor-pointer" v-if="data.prev_page_url && data.current_page > 3"><a class="page-link">...</a></li>

                <li v-for="page in (data.last_page)" :key="page" class="page-item" :class="{active : data.current_page == page}">
                    <a v-if="page < data.current_page + 3 && page > data.current_page - 3" class="page-link cursor-pointer" @click="paginate(page)">{{page}}</a>
                </li>

                <li class="page-item cursor-pointer" v-if="data.next_page_url && data.current_page > 3"><a class="page-link">...</a></li>

                <li class="page-item cursor-pointer" @click="gotoNextPage()" v-if="data.next_page_url">
                    <a class="page-link">&#9002;</a>
                </li>
                <li class="page-item cursor-pointer" v-if="data.current_page != data.last_page" @click="gotoPage(data.last_page)">
                    <a class="page-link">Last</a>
                </li>
            </ul>
        </nav>

    </div>
</template>

<script>
    export default {
        props: ["data", "show"],
        methods: {
            paginate(page)
            {
                let _this = this;
                _this.data.current_page = page;
                _this.$emit('updatePagination', _this.data);
            },
            gotoNextPage()
            {
                let _this = this;
                if (_this.data.current_page !== _this.data.last_page) {
                    _this.data.current_page = _this.data.current_page + 1;
                    _this.$emit('updatePagination', _this.data);
                }
            },
            gotoPreviousPage()
            {
                let _this = this;
                if (_this.data.current_page > 1) {
                    _this.data.current_page = _this.data.current_page - 1;
                    _this.$emit('updatePagination', _this.data);
                }
            },
            gotoPage(id)
            {
                let _this = this;
                _this.data.current_page = id;
                _this.$emit('updatePagination', _this.data);
            }
        }
    };
</script>
