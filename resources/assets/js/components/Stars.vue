<template>
	<div class="rating">
		<span v-on:click="rate(7)" :class="{selected: stars >= 7}">☆</span>
		<span v-on:click="rate(6)" :class="{selected: stars >= 6}">☆</span>
		<span v-on:click="rate(5)" :class="{selected: stars >= 5}">☆</span>
		<span v-on:click="rate(4)" :class="{selected: stars >= 4}">☆</span>
		<span v-on:click="rate(3)" :class="{selected: stars >= 3}">☆</span>
		<span v-on:click="rate(2)" :class="{selected: stars >= 2}">☆</span>
		<span v-on:click="rate(1)" :class="{selected: stars >= 1}">☆</span>
	</div>
</template>
<script>
export default {
	data: function() {
		return {
			stars: 1
		}
	},
	methods: {
	rate: function(star) {
			this.stars = star;
			this.$emit('changed', this.stars);

		}
	}
}
</script>
<style lang="scss" scoped>
.rating {
	unicode-bidi: bidi-override;
	direction: rtl;
	text-align: center;
	
	> span {
		display: inline-block;
		position: relative;
		width: 1.1em;
		cursor: pointer;
		
		/*&:hover, &:hover ~ span, */
		&.selected {
			color: transparent;
			
			&::before {
			   content: "\2605";
			   position: absolute;
			   left: 0; 
			   color: gold;
			}
		}
	}
}
</style>