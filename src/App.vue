<template>
	<main class="dark:bg-slate-900 bg-neutral-100 w-screen h-screen inline-block">
		<ToggleSwitchComponent />
		<GameList
			:games="games"
			class="mx-auto"
		/>
	</main>
</template>

<script setup>
import GameList from "@/components/GameListComponent.vue";
import ToggleSwitchComponent from "@/components/ToggleSwitchComponent.vue";

const games = [
	{ id: 1, name: "call of duty" },
	{ id: 2, name: "battlefield" },
];
</script>
