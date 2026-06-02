<div
    x-data="{
        open: false,

        material: {
            id: null,
            name: '',
            stock: '',
            unit: ''
        },

        type: 'adjustment_add',
        quantity: '',
        notes: '',

        loading: false,
        errors: {},

        async submitAdjustment() {

            this.loading = true;
            this.errors = {};

            try {

                const response = await fetch(

                    `/owner/raw-materials/${this.material.id}/adjustment`,

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

                            type: this.type,

                            quantity: this.quantity,

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

                    'adjustment-toast',

                    JSON.stringify({

                        title:
                            this.type === 'adjustment_add'
                                ? 'Stock Added'
                                : 'Stock Reduced',

                        message:
                            this.type === 'adjustment_add'
                                ? `${this.quantity} ${this.material.unit} added to opened stock.`
                                : `${this.quantity} ${this.material.unit} removed from opened stock.`

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

                this.errors = {

                    quantity: [
                        'Unable to save adjustment.'
                    ]

                };

            }

            finally {

                this.loading = false;

            }

        }
    }"

    @open-adjustment-modal.window="
        material = $event.detail;

        type = 'adjustment_add';
        quantity = '';
        notes = '';

        errors = {};

        open = true;
    "
>

    <x-modals.base-modal
        title="Stock Adjustment"
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

            <!-- CURRENT STOCK -->
            <div>

                <div
                    class="text-xs font-medium uppercase
                        tracking-wide text-[#8a8a8a]"
                >
                    Current Opened Stock
                </div>

                <div
                    class="mt-1 font-semibold text-[#2f2f2f]"
                >

                    <span x-text="material.stock"></span>

                    <span x-text="material.unit"></span>

                </div>

            </div>

            <!-- TYPE -->
            <div>

                <label
                    class="text-xs font-medium uppercase
                        tracking-wide text-[#8a8a8a]"
                >
                    Adjustment Type
                </label>

                <select
                    x-model="type"

                    class="w-full mt-2 h-11 rounded-2xl
                        border border-[#e8e8e5]
                        bg-white px-4
                        text-sm text-[#2f2f2f]
                        transition duration-200
                        focus:outline-none
                        focus:ring-2
                        focus:ring-black/5
                        focus:border-[#d8d8d5]"
                >

                    <option value="adjustment_add">
                        Add Stock
                    </option>

                    <option value="adjustment_reduce">
                        Reduce Stock
                    </option>

                </select>

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

                    step="0.01"

                    min="0.01"

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
                    <span x-show="material.unit === 'pcs'">
                        e.g. 1 <span x-text="material.unit"></span> (whole number)
                    </span>

                    <span x-show="material.unit !== 'pcs'">
                        e.g. 0.5 <span x-text="material.unit"></span>
                    </span>
                </p>

                <div
                    x-show="errors.quantity"
                    x-text="errors.quantity?.[0]"
                    class="mt-2 text-xs text-rose-600"
                ></div>

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

                    placeholder="e.g. Damaged package, stock recount correction, etc..."

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

                    @click="submitAdjustment()"

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
                        Confirm Adjustment
                    </span>

                    <span x-show="loading">
                        Saving...
                    </span>

                </button>

            </div>

        </x-slot:footer>

    </x-modals.base-modal>

</div>