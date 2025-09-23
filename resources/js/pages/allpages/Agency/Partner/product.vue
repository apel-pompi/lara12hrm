<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import PartnerLayout from '@/pages/allpages/Agency/Partner/partnerlayout.vue';
import { Plus,Trash } from 'lucide-vue-next';

const props = defineProps<{
    partner: { id: number; status: string };
    product: { id: number; name: string };
}>();
console.log(props.product);
</script>

<template>
    <PartnerLayout :partner="props.partner">
        <div class="space-y-4">
            <div>
                <Button variant="outline" size="sm" @click="goToDocumentType"><Plus></Plus>Add Product</Button>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Product Name</TableHead>
                        <TableHead>Product Type</TableHead>
                        <TableHead>Associated Partner</TableHead>
                        <TableHead>Partner Branch</TableHead>
                        <TableHead>Enrolled</TableHead>
                        <TableHead>Intake Month</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(product, index) in props.product" :key="product.id ?? index">
                        <TableCell>{{ product.name }}</TableCell>
                        <TableCell>{{ product.productype.producttypename }}</TableCell>
                        <TableCell>{{ product.partner.name }}</TableCell>
                        <TableCell>
                            <Badge class="m-0.5 p-1" variant="outline" v-for="(b, idx) in product.branch_name" :key="idx">
                                {{ b }}
                            </Badge>
                        </TableCell>
                        <TableCell></TableCell>
                        <Badge class="m-0.5 p-1" variant="outline" v-for="(b, idx) in product.intak_month.split(',')" :key="idx">
                            {{ b }}
                        </Badge>
                        <TableCell>
                            <Switch v-model="product.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(product)"> </Switch>
                        </TableCell>
                        <TableCell class="text-right">
                            <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(product.id)"><Trash></Trash></Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </PartnerLayout>
</template>
