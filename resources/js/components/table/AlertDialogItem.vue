<script lang="ts" setup>
import {
	AlertDialog,
	AlertDialogAction,
	AlertDialogCancel,
	AlertDialogContent,
	AlertDialogDescription,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogTitle,
	AlertDialogTrigger
} from '@/components/ui/alert-dialog';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';

defineProps<{
	dropdownText: string;
	title: string;
	description: string;
	cancel: string;
	action: string;
}>();

const emit = defineEmits<{
	(e: 'submit'): void;
	(e: 'opened'): void;
	(e: 'closed'): void;
}>();
</script>

<template>
	<AlertDialog>
		<AlertDialogTrigger as-child>
			<DropdownMenuItem @select.prevent @click="emit('opened')">
				{{ dropdownText }}
			</DropdownMenuItem>
		</AlertDialogTrigger>

		<AlertDialogContent>
			<AlertDialogHeader>
				<AlertDialogTitle>
					{{ title }}
				</AlertDialogTitle>
				<AlertDialogDescription>
					{{ description }}
				</AlertDialogDescription>
			</AlertDialogHeader>
			<AlertDialogFooter>
				<AlertDialogCancel @click="emit('closed')">{{ cancel }}</AlertDialogCancel>
				<form @submit.prevent="emit('submit')">
					<AlertDialogAction type="submit">{{ action }}</AlertDialogAction>
				</form>
			</AlertDialogFooter>
		</AlertDialogContent>
	</AlertDialog>
</template>
