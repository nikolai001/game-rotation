<template>
	<label class="mt-2 flex group w-fit items-center ml-auto mr-2">
		<span
			class="dark:text-neutral-100 text-slate-900"
			v-text="isDark ? 'Toggle light mode' : 'Toggle dark mode'"
		/>

		<input
			type="checkbox"
			v-model="isDark"
			class="peer appearance-none"
			@click="toggleDark()"
		/>
		<span class="w-10 h-4 flex items-center flex-shrink-0 ml-4 p-1 bg-gray-300 rounded-full duration-300 ease-in-out peer-checked:bg-green-400 after:w-3 after:aspect-square after:bg-white after:rounded-full after:shadow-md after:duration-300 peer-checked:after:translate-x-5 group-hover:after:translate-x-1"></span>
	</label>
</template>

<script setup>
import { useDark, useToggle } from "@vueuse/core";

const isDark = useDark();

const toggleDark = useToggle(isDark);
</script>
