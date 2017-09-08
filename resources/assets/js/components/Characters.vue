<template>
	<div class="flex-vertical-centered wide">
		<h3 class="subhead">Filter characters</h3>
		<div class="filters">
			<input v-model="characterFilter" placeholder="Character name">
			<stars v-on:changed="filterStars"></stars>
		</div>
	    <list 
	    	:columns="columns"
	    	:items="filtered"
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
                route: 'characters',
                items: [],
                columns: [
		            { prop: 'name', label: 'Character' },
		            { prop: 'member.name', label: 'Member' },
		            { prop: 'rarity', label: 'Rarity' },
		            { prop: 'level', label: 'Level' },
		            { prop: 'gear_level', label: 'Gear' },
		        ],
		        characterFilter: "",
		        minStars: 0,
            }
        },
        computed: {
        	filtered: function() {
        		return this.items.filter(item => {
        			return item.name.toLocaleLowerCase().indexOf(this.characterFilter.toLocaleLowerCase()) >= 0
        				&& item.rarity >= this.minStars;
        		});
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
            filterStars: function(stars) {
            	this.minStars = stars;
            }
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