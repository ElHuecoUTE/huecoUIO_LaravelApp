<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger
} from '@/components/ui/dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';
import { Loader2 } from 'lucide-vue-next';

defineProps<{
	dropdownText: string;
	title: string;
	description: string;
	disabled: boolean;
	action: string;
}>();

const emit = defineEmits<{
	(e: 'opened'): void;
	(e: 'submit'): void;
}>();
</script>

<template>
	<Dialog>
		<DialogTrigger as-child>
			<DropdownMenuItem @select.prevent @click="emit('opened')">
				{{ dropdownText }}
			</DropdownMenuItem>
		</DialogTrigger>

		<DialogContent class="sm:max-w-[425px]">
			<form @submit.prevent="emit('submit')">
				<DialogHeader>
					<DialogTitle>
						{{ title }}
					</DialogTitle>
					<DialogDescription>
						{{ description }}
					</DialogDescription>
				</DialogHeader>
				<div class="py-4">
					<slot />
				</div>
				<DialogFooter>
					<Button type="submit" :disabled="disabled">
						<Loader2 v-if="disabled" class="mr-2 h-4 w-4 animate-spin" />
						{{ action }}
					</Button>
				</DialogFooter>
			</form>
		</DialogContent>
	</Dialog>
</template>
