<template>
	<div class="absolute bg-neutral-300 dark:bg-slate-800 w-4/12 min-w-44 max-w-80 aspect-video rounded-md shadow flex flex-col justify-center translate-y-8">
		<mdicon
			v-if="loading"
			class="dark:text-neutral-100 text-slate-900 self-center mt-auto"
			name="loading"
			spin
		/>
		<span
			class="dark:text-neutral-100 text-slate-900 self-center mt-auto text-center"
			v-text="'Please input pin for list'"
		/>

		<input
			type="text"
			class="outline-none w-1/2 self-center rounded px-2 py-1 my-1 text-center bg-neutral-200 dark:bg-slate-700 dark:text-neutral-100 text-slate-900"
			placeholder="Pincode"
		>

		<div class="flex mt-auto mb-5 justify-evenly w-2/3 mx-auto">
			<button
				class="bg-green-400 rounded-md shadow hover:shadow-md transition-shadow py-1 px-3 h-fit dark:text-neutral-100 text-slate-900"
				v-text="'Confirm'"
				@click="fetchList()"
			/>

			<button
				class="bg-red-400 rounded-md shadow hover:shadow-md transition-shadow py-1 px-3 h-fit dark:text-neutral-100 text-slate-900"
				v-text="'Cancel'"
				@click="$emit('close')"
			/>
		</div>
		<span
			v-text="'Wrong pin'"
			class="text-red-400 text-center mb-2"
		/>
	</div>
</template>

<script setup>
import { ref, defineEmits } from "vue";

defineEmits(["close"]);

const loading = ref(false);

function fetchList() {
	console.log(123);
}
</script>