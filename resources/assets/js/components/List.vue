<template>
	<div class="flex-vertical-centered wide">
		<div class="total">Total: <span>{{ items.length }}</span></div>
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
		<div class="total">Total: <span>{{ items.length }}</span></div>
    </div>
</template>

<script>
    export default {
        props: {
            items: Array,
            columns: Array,
        },
        data: function () {
            return {
				sorted: this.columns[0].prop,
				reversed: false
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
            	this.reversed = this.sorted === prop && !this.reversed;
            	this.sorted = prop;
                this.$emit('sort', prop, this.reversed);
            }
        }
    }
</script>

<style lang="scss" scoped>
	.total {
		font-weight: bolder;
		> span {
			font-weight: normal;
		}
	}
    .clickable {
        cursor: pointer;

        &:hover {
            text-decoration: underline;
        }
    }
</style>