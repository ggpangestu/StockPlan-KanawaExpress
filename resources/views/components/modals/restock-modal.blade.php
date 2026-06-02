<div
    x-data="{
        open: false,

        material: {
            id: null,
            name: '',
            totalStock: '',
            sealedStock: '',
            purchaseUnit: '',
            unit: '',
            latestPrice: null
        },

        quantity: '',
        price: '',
        notes: '',

        loading: false,
        errors: {},

        async submitRestock() {

            this.loading = true;

            this.errors = {};

            try {

                const response = await fetch(

                    `/owner/raw-materials/${this.material.id}/restock`,

                    {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',

                            'Accept': 'application/json',

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name=csrf-token]'
                                    )
                                    .content
                        },

                        body: JSON.stringify({

                            quantity: this.quantity,

                            latest_price: this.price,

                            notes: this.notes

                        })

                    }
                );

                const data =
                    await response.json();

                if (!response.ok) {

                    this.errors =
                        data.errors ?? {};

                    return;
                }

                this.errors = {};

                this.open = false;

                sessionStorage.setItem(

                    'restock-toast',

                    JSON.stringify({

                        title: 'Restock Completed',

                        message:
                            `${this.quantity} ${this.material.purchaseUnit} added to inventory.`

                    })

                );

                setTimeout(() => {

                    const content =
                        document.getElementById(
                            'main-content'
                        );

                    sessionStorage.setItem(
                        'scroll-position',
                        content?.scrollTop ?? 0
                    );

                    window.location.reload();

                }, 250);

            }

            catch (error) {

                console.error(error);

            }

            finally {

                this.loading = false;

            }

        }
    }"

    @open-restock-modal.window="
        material = $event.detail;

        quantity = '';
        price = '';
        notes = '';

        errors = {};

        open = true;
    "
>

    <x-modals.base-modal
        title="Restock Material"
        maxWidth="md"
    >

        <div class="space-y-6">

            <!-- MATERIAL -->
            <div>

                <div
                    class="text-xs font-medium uppercase
                        tracking-wide text-[#8a8a8a]"
                >
                    Material Name
                </div>

                <div
                    class="mt-1 font-semibold text-[#2f2f2f]"
                    x-text="material.name"
                ></div>

            </div>

            <!-- CURRENT INVENTORY -->
            <div>

                <div
                    class="text-xs font-medium uppercase
                        tracking-wide text-[#8a8a8a]"
                >
                    Current Inventory
                </div>

                <div
                    class="mt-1 font-semibold text-[#2f2f2f]"
                >

                    <div class="mt-2 space-y-1">

                        <div class="flex justify-between">

                            <span class="text-[#8a8a8a]">
                                Total Stock
                            </span>

                            <span class="font-semibold text-[#2f2f2f]">

                                <span x-text="material.totalStock"></span>

                                <span
                                    class="text-[#8a8a8a]"
                                    x-text="material.unit"
                                ></span>

                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-[#8a8a8a]">
                                Sealed Stock
                            </span>

                            <span class="font-semibold text-[#2f2f2f]">

                                <span x-text="material.sealedStock"></span>

                                <span 
                                    class="text-[#8a8a8a]"
                                    x-text="material.purchaseUnit"
                                ></span>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- QUANTITY -->
            <div>

                <label
                    class="text-xs font-medium uppercase
                        tracking-wide text-[#8a8a8a]"
                >
                    Quantity
                </label>

                <input
                    x-model="quantity"

                    type="number"

                    step="1"
                    min="1"

                    required

                    class="w-full mt-2 h-11 rounded-2xl
                        border border-[#e8e8e5]
                        px-4
                        text-sm text-[#2f2f2f]
                        transition duration-200
                        focus:outline-none
                        focus:ring-2
                        focus:ring-black/5
                        focus:border-[#d8d8d5]"

                    :class="{
                        'border-rose-300': errors.quantity
                    }"
                >

                <p
                    class="mt-2 text-xs text-[#8a8a8a]"
                >
                    Enter purchased quantity in
                    <span x-text="material.purchaseUnit"></span>
                </p>

                <div
                    x-show="errors.quantity"
                    x-text="errors.quantity?.[0]"
                    class="mt-2 text-xs text-rose-600"
                ></div>

            </div>

            <!-- PURCHASE PRICE -->
            <div>

                <label
                    class="text-xs font-medium uppercase
                        tracking-wide text-[#8a8a8a]"
                >
                    Purchase Price
                </label>

                <div
                    x-show="!material.latestPrice"
                    class="mt-2 rounded-2xl
                        border border-amber-200
                        bg-amber-50
                        px-4 py-3
                        text-xs text-amber-700"
                >

                    ⚠ First restock requires purchase price because no previous price has been recorded.

                </div>

                <div class="relative mt-2">

                    <div
                        class="absolute inset-y-0 left-0
                            flex items-center
                            pl-4
                            text-sm text-[#8a8a8a]
                            pointer-events-none"
                    >
                        Rp
                    </div>

                    <input
                        x-model="price"

                        type="number"

                        min="0"

                        step="1"

                        class="w-full h-11 rounded-2xl
                            border border-[#e8e8e5]
                            pl-10 pr-4
                            text-sm text-[#2f2f2f]
                            transition duration-200
                            focus:outline-none
                            focus:ring-2
                            focus:ring-black/5
                            focus:border-[#d8d8d5]"

                        :class="{
                            'border-rose-300': errors.latest_price
                        }"

                        :placeholder="
                            material.latestPrice
                                ? material.latestPrice
                                : '85000'
                        "
                    >

                </div>

                <div
                    x-show="errors.latest_price"
                    x-text="errors.latest_price?.[0]"
                    class="mt-2 text-xs text-rose-600"
                ></div>

                <p
                    class="mt-2 text-xs text-[#8a8a8a]"
                    x-show="material.latestPrice"
                >

                    Last purchase price:

                    Rp

                    <span
                        x-text="material.latestPrice"
                    ></span>

                </p>

            </div>

            <!-- NOTES -->
            <div>

                <label
                    class="text-xs font-medium uppercase
                        tracking-wide text-[#8a8a8a]"
                >
                    Notes
                </label>

                <textarea
                    x-model="notes"

                    placeholder="e.g. Supplier A, weekly purchase..."

                    rows="3"

                    class="w-full mt-2 rounded-2xl
                        border border-[#e8e8e5]
                        px-4 py-3
                        resize-none
                        text-sm text-[#2f2f2f]
                        transition duration-200
                        focus:outline-none
                        focus:ring-2
                        focus:ring-black/5
                        focus:border-[#d8d8d5]"
                ></textarea>

            </div>

        </div>

        <x-slot:footer>

            <div class="flex justify-end gap-3">

                <button
                    type="button"

                    @click="open = false"

                    :disabled="loading"

                    class="inline-flex items-center justify-center
                        h-10 px-5 rounded-2xl
                        border border-[#e8e8e5]
                        bg-white text-[#2f2f2f]
                        text-sm font-medium
                        hover:bg-[#f3f3f1]
                        transition duration-200
                        disabled:opacity-50
                        disabled:cursor-not-allowed
                    "
                >
                    Cancel
                </button>

                <button
                    type="button"

                    @click="submitRestock()"

                    :disabled="loading"

                    class="inline-flex items-center justify-center
                        h-10 px-5 rounded-2xl
                        bg-[#2f2f2f]
                        text-white
                        text-sm font-medium
                        hover:opacity-90
                        transition duration-200
                        disabled:opacity-50
                        disabled:cursor-not-allowed"
                >

                    <span x-show="!loading">
                        Confirm Restock
                    </span>

                    <span x-show="loading">
                        Saving...
                    </span>

                </button>

            </div>

        </x-slot:footer>

    </x-modals.base-modal>

</div>