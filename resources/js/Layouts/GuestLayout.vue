<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
	<div class="flex min-h-screen flex-col items-center justify-center bg-gray-100 px-8 md:px-0">
		<div>
			<Link href="/">
				<ApplicationLogo class="h-24 w-24 fill-current text-gray-500" />
			</Link>
		</div>

		<div class="mt-6 w-full overflow-hidden px-6 py-4 sm:max-w-md sm:rounded-lg">
			<slot />
		</div>
	</div>
	<Toaster />
</template>
