<template>
    <list 
    	:columns="columns"
    	:items="items"
    	v-on:sort="sort"
    ></list>
</template>

<script>
    export default {
        props: {
            route: String,
        },
        mounted() {
            this.$http.get(this.route)
                .then(res => {
	                	console.warn("got result", this, this.items);
                    this.items = res.data;
                    this.sort(this.columns[0].prop, false);
				});
        },
        data: function () {
            return {
                items: [],
                columns: [
		            { prop: 'name', label: 'Character' },
		            { prop: 'member.name', label: 'Member' },
		            { prop: 'rarity', label: 'Rarity' },
		            { prop: 'level', label: 'Level' },
		            { prop: 'gear_level', label: 'Gear' },
		        ]
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
            sort: function(prop, reversed) {
                this.items = this.items.sort((a, b) => {
					let aProp = this.resolve(a, prop);
					let bProp = this.resolve(b, prop);
                    if (typeof aProp === typeof "") {
                        return aProp.localeCompare(bProp);
                    }
                    return aProp - bProp;
                });
				
				if (reversed) {
					this.items = this.items.reverse();
				}
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>