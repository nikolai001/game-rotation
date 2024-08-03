<template>
	<section class="rounded-md dark:bg-slate-700 bg-neutral-200 w-1/2 max-w-96 py-2 px-4 flex flex-col shadow-md mt-10">
		<div
			v-if="gameList"
			class="flex flex-col items-center"
		>
			<div
				v-for="game in props.games"
				:key="game.id"
				class="dark:text-neutral-100 text-slate-900"
			>
				{{ game.name }}
			</div>
		</div>
		<p
			v-else
			v-text="'No games :('"
			class="dark:text-neutral-100 text-slate-900"
		>
		</p>
		<button
			v-if="gameList"
			v-text="'Fresh rotation'"
			class="self-end bg-green-400 rounded-md px-2 py-1 dark:text-neutral-100 text-slate-900 shadow hover:shadow-md transition-shadow mt-2"
		/>
	</section>
</template>

<script setup>
import { computed, defineProps } from "vue";

const props = defineProps({
	games: Object,
});

const gameList = computed(() => {
	if (props.games.length > 1) return true;
	return false;
});
</script>
