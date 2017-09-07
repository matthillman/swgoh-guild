<template>
    <table>
        <thead>
            <tr>
                <th v-for="column in columns" 
                    v-on:click="sortBy(column.prop)"
                    class="clickable"
                    v-bind:class="{sorted: sorted === column.prop}"
                >{{ column.label }}</th>
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
                .then(res => {
                    this.items = res.data;
                    this.sortBy(this.columns[0].prop);
				});
        },
        data: function () {
            return {
                items: [],
				sorted: ''
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
				let vm = this;
                this.items = this.items.sort(function(a, b) {
					let aProp = vm.resolve(a, prop);
					let bProp = vm.resolve(b, prop);
                    if (typeof aProp === typeof "") {
                        return aProp.localeCompare(bProp);
                    }
                    return aProp - bProp;
                });
				
				if (this.sorted === prop) {
					this.items = this.items.reverse();
					this.sorted = '';
				} else {
					this.sorted = prop;
				}
            }
        }
    }
</script>

<style lang="scss" scoped>
    .clickable {
        cursor: pointer;

        &:hover {
            text-decoration: underline;
        }
    }
</style>