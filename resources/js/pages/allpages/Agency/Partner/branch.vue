<script setup lang="ts">
import PartnerLayout from '@/pages/allpages/Agency/Partner/partnerlayout.vue';
import { Plus, SquarePen, Trash } from 'lucide-vue-next';
import Switch from '@/components/ui/switch/Switch.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
const props = defineProps<{
   partner: { id: number;status:string };
   branch:{id:number;name:string}
}>();
</script>

<template>
    <PartnerLayout :partner="props.partner">
        <div class="space-y-4">
            <div>
                <Button variant="outline" size="sm" @click="goToDocumentType"><Plus></Plus>Add Branch</Button>
            </div>
            <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Partner Name</TableHead>
                            <TableHead>Branch Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Country Name</TableHead>
                            <TableHead>State Name</TableHead>
                            <TableHead>City Name</TableHead>
                            <TableHead>Total Usage</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Added By</TableHead>
                            <TableHead class="text-center">Action</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(partnerbranch, index) in props.branch" :key="partnerbranch.id ?? index">
                            <TableCell>{{ partnerbranch.partner.name }}</TableCell>
                            <TableCell>{{ partnerbranch.branch_name }}</TableCell>
                            <TableCell>{{ partnerbranch.branch_email }}</TableCell>
                            <TableCell>{{ partnerbranch.states?.country.name ?? '-'  }}</TableCell>
                            <TableCell>{{ partnerbranch.states?.name ?? '-'  }}</TableCell>
                            <TableCell>{{ partnerbranch.citys?.name ?? '-' }}</TableCell>
                            <TableCell></TableCell>
                            <TableCell>
                                <Switch v-model="partnerbranch.active" :checked-value="1" :unchecked-value="0" @click="toggleStatus(partnerbranch)"> </Switch>
                            </TableCell>
                            <TableCell>{{ partnerbranch.user.name }}</TableCell>
                            <TableCell class="text-right">
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onEdit(partnerbranch.id)"><SquarePen></SquarePen></Button>
                                <Button class="m-[2px]" size="sm" variant="outline" @click="onDelete(partnerbranch.id)"><Trash></Trash></Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
        </div>
    </PartnerLayout>
</template>
