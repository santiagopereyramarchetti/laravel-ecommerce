import { ref, watch, computed } from 'vue';
import { router } from '@inertiajs/vue3';

export default (params) => {

    const { filters: defaultFilters, routeResourceName } = params

    const filters = ref(defaultFilters)

    const isFilled = computed(() => {
        return Object.values(filters.value).some(v => !["", null, undefined].includes(v))
    })
    
    const isLoading = ref(false)

    const fetchItems = () =>{
        router.get(route(`admin.${routeResourceName}.index`, {
                ...filters.value,
                page: 1,
            }), {}, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            onBefore: () => {
                isLoading.value = true
            },
            onFinish: () => {
                isLoading.value = false
            } 
        })
    }

    const fetchItemsHandler = ref(null)


    watch(filters, () => {
        clearTimeout(fetchItemsHandler.value)
        /*Limpia el tiempo de setTimeout. Por lo tanto cada vez que cambia filters.name, no reaiza la petición medainte fetchitems porque se reinicia el tiempo,
            Solo la hace la última vez porque apsa el tiempo seteado en settimeout y no se ejecuta clearTimeout
        */
        fetchItemsHandler.value = setTimeout(() => {
            fetchItems()
        }, 300)
    },{
        deep: true
    })

    return {
        filters,
        isFilled,
        isLoading,
        fetchItemsHandler,
        watch
    }
}