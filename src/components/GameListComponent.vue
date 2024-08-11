<template>
	<section class="rounded-md dark:bg-slate-700 bg-neutral-200 w-1/2 max-w-96 py-2 px-4 flex flex-col shadow-md mt-10">
		<div
			v-if="gameList"
			class="flex flex-col items-center"
		>
			<div
				v-for="game in props.games"
				:key="game.id"
				class="dark:text-neutral-100 text-slate-900 text-center"
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
			@click="openDialog = true"
		/>
		<RotationDialogComponent
			v-if="openDialog"
			class="self-center"
			:dialog-message="insult()"
			:games="games"
			@close="openDialog = false"
			@reload-data="$emit('reload-data')"
		/>
	</section>
</template>

<script setup>
import { computed, defineProps, ref } from "vue";
import dayjs from "dayjs";

import RotationDialogComponent from "@/components/RotationDialogComponent.vue";

const props = defineProps({
	games: Object,
	lastUpdate: String,
});

const dayJs = dayjs();

const dateShort = [
	"Omg der er kun gået x antal dage din noob",
	"Keder du dig seriøst allerede..?",
	"Nåååå vi prøver rigtigt nok at snyde listen lille Pede",
	"Har du fået lov til det her?",
	"Enten så lyver du lige nu ellers spiller vi for meget",
];
const dateLong = [
	"Ja det jo typisk dig... aldrig tid til at spille",
	"DER ER GÅET x DAGE DIN NOOB",
	"hvorfor spiller vi aldrig",
];

const sameDay = [
	"DER ER GÅET EN DAG",
	"SÅ SLAPPER DU",
	"DER ER IKKE ENGANG GÅET ÈN DAG",
];

const openDialog = ref(false);

const gameList = computed(() => {
	if (props.games.length >= 1) return true;
	return false;
});

function insult() {
	if (props.lastUpdate) {
		let lastDateUpdated = dayjs(props.lastUpdate);

		if (dayJs.isSame(lastDateUpdated, "day")) {
			const randomIndex = Math.floor(Math.random() * sameDay.length);
			return sameDay[randomIndex];
		} else if (dayJs.isBefore(lastDateUpdated.add(14, "day"))) {
			const randomIndex = Math.floor(Math.random() * dateShort.length);
			return dateShort[randomIndex].replace(
				"x",
				lastDateUpdated.diff(dayJs, "day")
			);
		} else if (dayJs.isAfter(lastDateUpdated.add(1, "month"))) {
			const randomIndex = Math.floor(Math.random() * dateLong.length);
			return dateLong[randomIndex].replace(
				"x",
				dayJs.diff(lastDateUpdated, "day")
			);
		} else
			return "Det er første gang du er tilfredsstillende tidsmæssigt... siger Sandra også";
	}
	return "Are you sure?";
}
</script>
