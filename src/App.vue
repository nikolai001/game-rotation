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
import { onMounted, ref } from "vue";

const games = ref({});

onMounted(async () => {
	try {
		let response = await fetch("http://localhost:8080/games.json");
		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}
		let data = await response.json();
		games.value = data.games;
	} catch (e) {
		console.error(e);
	}
});
</script>
