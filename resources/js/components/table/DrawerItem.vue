<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
	Drawer,
	DrawerClose,
	DrawerContent,
	DrawerDescription,
	DrawerFooter,
	DrawerHeader,
	DrawerTitle,
	DrawerTrigger
} from '@/components/ui/drawer';
import { DropdownMenuItem } from '@/components/ui/dropdown-menu';

defineProps<{
	dropdownText: string;
	title: string;
	description: string;
}>();
</script>

<template>
	<Drawer>
		<DrawerTrigger as-child>
			<DropdownMenuItem @select.prevent>
				{{ dropdownText }}
			</DropdownMenuItem>
		</DrawerTrigger>

		<DrawerContent>
			<div class="mx-auto w-full max-w-sm">
				<DrawerHeader>
					<DrawerTitle>{{ title }}</DrawerTitle>
					<DrawerDescription>{{ description }}</DrawerDescription>
				</DrawerHeader>
				<slot />
				<DrawerFooter>
					<DrawerClose as-child>
						<Button variant="outline"> Cerrar </Button>
					</DrawerClose>
				</DrawerFooter>
			</div>
		</DrawerContent>
	</Drawer>
</template>
