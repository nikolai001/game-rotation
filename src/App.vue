<template>
	<main class="dark:bg-slate-900 bg-neutral-100 w-screen h-screen inline-block">
		<ToggleSwitchComponent />
		<GameList
			:games="games"
			:last-update="lastUpdate"
			class="mx-auto"
			@reload-data="fetchOrderedList()"
		/>
	</main>
</template>
  
  <script setup>
import GameList from "@/components/GameListComponent.vue";
import ToggleSwitchComponent from "@/components/ToggleSwitchComponent.vue";
import { onMounted, ref } from "vue";

const games = ref([]);
const lastUpdate = ref("");

async function fetchInitialGamesList() {
	try {
		let response = await fetch("games.json");
		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}
		let data = await response.json();
		games.value = data.games;
	} catch (e) {
		console.error("Error fetching initial games list:", e);
	}
}

async function fetchOrderedList() {
	try {
		let response = await fetch("fetchGames.php");
		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}
		let data = await response.json();

		if (data.length > 0 && data[0] && data[0].games) {
			games.value = data[0].games.games;
			console.log(data[0].updated_at);
			lastUpdate.value = data[0].updated_at;
		} else {
			// Fallback to initial games list if database data is not available or empty
			fetchInitialGamesList();
		}
	} catch (e) {
		console.error("Error fetching data from backend:", e);
		fetchInitialGamesList();
	}
}

onMounted(async () => {
	fetchOrderedList();
});
</script>
  