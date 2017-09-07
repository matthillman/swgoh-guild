<template>
    <table>
        <thead>
            <tr>
                <th v-for="column in columns" v-on:click="sortBy(column.label)">{{ column.label }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in items">
                <td v-for="column in columns">{{ resolve(item, column.prop) }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: {
            route: String,
            columns: Array,
        },
        mounted() {
            this.$http.get(this.route)
                .then(res => this.items = res.data);
        },
        data: function () {
            return {
                items: []
            }
        },
        methods: {
            resolve: function(item, prop) {
                let v = item;
                let props = prop.split('.');
                for (let i = 0; i < props.length; i++) {
                    if (!v) return undefined;
                    v = v[props[i]];
                }
                return v;
            },
            sortBy: function(prop) {
                this.items = this.items.sort(function(a, b) {
                    if (typeof a === typeof "") {
                        return a.localeCompare(b);
                    }
                    return a - b;
                })
            }
        }
    }
</script>