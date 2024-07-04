<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Card, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

defineProps<{
  status: any;
  clientes: {
    cli_id: number;
    cli_nom: string;
    cli_ape: string;
    cli_ema: string;
  }[];
}>();

</script>

<template>
	<Head title="Facturas" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold leading-tight text-gray-800">Facturas</h2>
		</template>
		<div class="px-4 py-12">
			<div class="mx-auto flex max-w-7xl flex-col gap-y-4 sm:px-6 lg:px-8">
        <Card>
          <CardHeader class="relative flex justify-center">
            <CardTitle>Facturas</CardTitle>
            <CardDescription>Lista de facturas</CardDescription>
            <p class="absolute right-0 mr-8 text-4xl font-bold">{{ clientes.length }}</p>
          </CardHeader>
        </Card>
        <div class="grid grid-cols-3 gap-4">
          <Card v-for="cliente in clientes" :key="cliente.cli_id">
            <CardHeader>
              <CardTitle>{{ cliente.cli_nom }} {{ cliente.cli_ape }}</CardTitle>
              <CardDescription>{{ cliente.cli_ema }}</CardDescription>
            </CardHeader>
            <CardFooter>
              <a
                :href="route('facturas.generate', { id: cliente.cli_id })"
              >
                <Button>Generar factura</Button>
              </a>
            </CardFooter>
          </Card>
        </div>

      </div>
		</div>
	</AuthenticatedLayout>
</template>

<style>
</style>
