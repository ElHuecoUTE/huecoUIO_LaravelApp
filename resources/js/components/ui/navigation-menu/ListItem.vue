<script lang="ts" setup>
defineProps<{
	href: string;
	title: string;
}>();
</script>

<template>
	<a
		:href="href"
		class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
	>
		<span class="text-sm font-medium leading-none">{{ title }}</span>
		<p class="line-clamp-2 text-sm leading-snug text-muted-foreground">
			<slot />
		</p>
	</a>
</template>
