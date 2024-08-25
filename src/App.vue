<template>
	<main class="dark:bg-slate-900 bg-neutral-100 w-screen h-screen inline-block">
		<div class="flex">

			<select
				name="gamelist"
				v-model="currentList"
				class="rounded w-1/4 max-w-48 dark:bg-slate-700 bg-neutral-200 dark:text-white mt-2 ml-2 text-center"
			>
				<option
					disabled
					selected
				>Select list</option>
				<option
					v-for="list in lists"
					:key="list.id"
					:value="list.id"
				>
					{{ list.name }}
				</option>
			</select>

			<ToggleSwitchComponent />
		</div>
		<GameList
			:games="games"
			:last-update="lastUpdate"
			class="mx-auto"
			@reload-data="fetchOrderedList()"
		/>
		<pinComponent
			v-if="pinPrompt"
			class="mx-auto"
		/>
	</main>
</template>
  
<script setup>
import GameList from "@/components/GameListComponent.vue";
import ToggleSwitchComponent from "@/components/ToggleSwitchComponent.vue";
import pinComponent from "@/components/pinComponent.vue";
import { inject, onMounted, ref, watch, defineEmits } from "vue";

const games = ref([]);
const lastUpdate = ref("");
const lists = ref([]);
const currentList = ref({});
const pinPrompt = ref(false);
const emitter = inject("emitter");

defineEmits(["reload-data", "close-modal"]);

emitter.on("close-modal", () => {
	currentList.value = null;
});

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

async function fetchAllLists() {
	try {
		let response = await fetch("/api/fetchList.php");
		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}
		let data = await response.json();

		if (data.length > 0) {
			lists.value = data;
		}
	} catch (e) {
		console.error("Error fetching data from backend:", e);
	}
}

async function fetchOrderedList() {
	try {
		let response = await fetch("/api/fetchGames.php");
		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}
		let data = await response.json();

		if (data.length > 0 && data[0] && data[0].games) {
			games.value = data[0].games.games;
			lastUpdate.value = data[0].updated_at;
		} else {
			fetchInitialGamesList();
		}
	} catch (e) {
		console.error("Error fetching data from backend:", e);
		fetchInitialGamesList();
	}
}

watch(
	() => currentList.value,
	() => {
		if (currentList.value) {
			pinPrompt.value = true;
		} else {
			pinPrompt.value = false;
		}
	}
);

onMounted(async () => {
	fetchOrderedList();
	fetchAllLists();
});
</script>
  