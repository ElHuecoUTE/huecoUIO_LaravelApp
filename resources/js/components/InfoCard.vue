<script lang="ts" setup>
defineProps<{
	title: string;
	subtitle: string;
	mainValue: number;
}>();

const numberFormat = (num: number) => {
	if (num > 999 && num < 1000000) {
		return (num / 1000).toFixed(1) + 'K';
	} else if (num > 1000000) {
		return (num / 1000000).toFixed(1) + 'M';
	} else if (num < 900) {
		return num;
	}
};
</script>

<template>
	<div class="flex flex-col items-center gap-y-2 space-x-4 rounded-md border bg-white p-4">
		<div class="flex-1 space-y-1">
			<p class="text-center text-2xl font-semibold leading-none">{{ title }}</p>
			<p class="text-center text-sm text-muted-foreground">{{ subtitle }}</p>
		</div>
		<p class="pr-2 text-5xl font-semibold leading-none">
			{{ numberFormat(mainValue) }}
		</p>
	</div>
</template>
