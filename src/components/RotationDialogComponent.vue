<template>
	<div class="absolute bg-neutral-300 dark:bg-slate-800 w-4/12 max-w-80 aspect-video rounded-md shadow flex flex-col justify-center translate-y-8">
		<mdicon
			v-if="loading"
			class="dark:text-neutral-100 text-slate-900 self-center mt-auto"
			name="loading"
			spin
		/>
		<span
			class="dark:text-neutral-100 text-slate-900 self-center mt-auto"
			v-text="props.dialogMessage"
		/>

		<div class="flex mt-auto mb-5 justify-evenly w-2/3 mx-auto">
			<button
				class="bg-green-400 rounded-md shadow hover:shadow-md transition-shadow py-1 px-3 h-fit dark:text-neutral-100 text-slate-900"
				v-text="'Confirm'"
				@click="uploadRotation()"
			/>

			<button
				class="bg-red-400 rounded-md shadow hover:shadow-md transition-shadow py-1 px-3 h-fit dark:text-neutral-100 text-slate-900"
				v-text="'Cancel'"
				@click="$emit('close')"
			/>
		</div>
		<span
			v-text="error"
			class="text-red-400 text-center mb-2"
		/>
	</div>
</template>
  
<script setup>
import { defineProps, defineEmits, ref } from "vue";
const emit = defineEmits(["reload-data", "close"]);
const props = defineProps({
	games: Object,
	dialogMessage: String,
});

const loading = ref(false);
const error = ref("");

function shuffleGames() {
	let shuffledGames = props.games.slice();

	for (let i = shuffledGames.length - 1; i > 0; i--) {
		const j = Math.floor(Math.random() * (i + 1));

		[shuffledGames[i], shuffledGames[j]] = [
			shuffledGames[j],
			shuffledGames[i],
		];
	}

	return shuffledGames;
}

async function uploadRotation() {
	loading.value = true;
	try {
		const response = await fetch("uploadGames.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({ games: shuffleGames() }),
		});

		if (!response.ok) {
			error.value = "Reshuffling of games failed";
			throw new Error(`HTTP error! status: ${response.status}`);
		}
		emit("reload-data");
		emit("close");
	} catch (error) {
		console.error("Error uploading data:", error);
		error.value = "Reshuffling of games failed";
	} finally {
		loading.value = false;
	}
	setTimeout(() => {
		error.value = "";
	}, 2000);
}
</script>
  