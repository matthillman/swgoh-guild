<template>
	<div class="flex-vertical-centered wide">
	    <list 
	    	:columns="columns"
	    	:items="items"
	    	v-on:sort="sort"
	    ></list>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$http.get(this.route)
                .then(res => {
                    this.items = res.data;
                    this.sort(this.columns[0].prop, false);
				});
        },
        data: function () {
            return {
                route: 'members',
                items: [],
                columns: [
		            { prop: 'name', label: 'Member' },
		            { prop: 'power', label: 'Galactic Power' },
		            { prop: 'character_power', label: 'Character Power' },
		            { prop: 'ship_power', label: 'Ship Power' },
		            { prop: 'seven_star', label: '7⭐️' },
		            { prop: 'gear_twelve', label: 'G12' },
		            { prop: 'gear_eleven', label: 'G11' },
		        ],
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
            },
        }
    }
</script>

<style lang="scss" scoped>
.subhead {
	font-size: 16px;
	font-weight: bold;
	margin: 0;
}
.filters {
	display: flex;
	margin-bottom: 24px;
	
	> * {
		margin-right: 12px;
	}
}
</style>