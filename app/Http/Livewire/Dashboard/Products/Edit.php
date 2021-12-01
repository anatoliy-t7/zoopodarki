<?php

namespace App\Http\Livewire\Dashboard\Products;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\BrandSerie;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product1C;
use App\Models\ProductUnit;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class Edit extends Component
{
    use WithPagination;
    use WireToast;
    use WithFileUploads;

    public $productId;
    public $product;
    public $name;
    public $description;
    public $meta_title;
    public $meta_description;
    public $consist;
    public $applying;
    public $newFiles = [];
    public $status = 'active';
    public $unitId;
    public $media = [];
    public $photos = [];
    public $att_selected;
    public $readyCategories = [];
    public $productBrand = [];
    public $productSerie;
    public $search;
    public $variations = [];
    public $variation;
    public $taken = true;
    public $emptyStock = true;
    public $attributes;
    public $categories;
    public $catalogs;
    public $brands;
    public $series;
    public $units;
    public $statuses;
    public $itemsPerPage = 30;
    protected $average;
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'itemsPerPage',
        'taken',
        'emptyStock',
    ];
    protected $listeners = [
        'getAttributes',
        'save',
        'setName',
        'removeVariation',
        'getBrandSeries',
        'getCatalogs',
    ];

    public function attributes()
    {
        return [
            'photos.*' => 'фотография',
        ];
    }

    public function updatedPhotos()
    {
        $this->validate(
            [
                'photos' => 'max:5',
                'photos.*' => 'image|mimes:jpeg,png,jpg|max:1024', // 1MB Max
            ],
            [
                'photos.max' => 'Вы можете загрузить максимум :max фотографий',
            ]
        );
    }

    public function mount()
    {
        $this->productId = request()->get('id');

        $this->productId = request()->query('id', $this->productId);

        if (Product::where('id', $this->productId)->first()) {
            $functionProduct = Product::where('id', $this->productId)
            ->with('media', 'categories', 'categories.catalog', 'variations', 'attributes', 'brand')
            ->first();
            $this->product = $functionProduct;
            $this->name = $functionProduct->name;
            $this->meta_title = $functionProduct->meta_title;
            $this->meta_description = $functionProduct->meta_description;
            $this->description = $functionProduct->description;
            $this->consist = $functionProduct->consist;
            $this->applying = $functionProduct->applying;
            $this->status = $functionProduct->status;
            $this->unitId = $functionProduct->unit_id;

            if (! $functionProduct->brand == null) {
                $this->productBrand[] = $functionProduct->brand->toArray();

                if ($functionProduct->serie) {
                    $serie = $functionProduct
                        ->serie()
                        ->pluck('id')
                        ->flatten();
                    $this->productSerie = $serie[0];
                }
            }

            $this->variations = $functionProduct->variations->toArray();

            $this->readyCategories = $functionProduct->categories->toArray();

            foreach ($functionProduct->categories as $key => $item) {
                $functionName = Catalog::where('id', $item->catalog_id)
                    ->get('name')
                    ->toArray();
                $catalogName = Arr::flatten($functionName);
                $this->readyCategories[$key]['catalogName'] = $catalogName[0];
            }

            $this->att_selected = $functionProduct->attributes->toArray();

            foreach ($functionProduct->attributes as $key => $item) {
                $functionName = Attribute::where('id', $item->attribute_id)
                    ->get('name')
                    ->toArray();
                $attName = Arr::flatten($functionName);
                $this->att_selected[$key]['attName'] = $attName[0];
            }

            if ($this->product->media()->count() > 0) {
                $this->media = $this->product->getMedia('product-images');
            }
        }

        $this->categories = Category::orderBy('name', 'ASC')->get();
        $this->brands = Brand::orderBy('name', 'ASC')->get();
        $this->units = ProductUnit::orderBy('name', 'ASC')->get();
        $this->statuses = config('constants.product_status');
    }

    public function completeUpload($uploadedUrl, $eventName)
    {
        foreach ($this->newFiles as $file) {
            if ($file->getFilename() === $uploadedUrl) {
                $newFileName = $file->store('/', 'public');

                $url = Storage::disk('public')->url($newFileName);
                $this->dispatchBrowserEvent($eventName, [
                    'url' => $url,
                    'href' => $url,
                ]);

                return;
            }
        }
    }

    public function removeFileAttachment($url)
    {
        try {
            Storage::disk('public')->delete($url);

            toast()
                ->info('Изображение удалено')
                ->push();
        } catch (\Throwable$th) {
            toast()
                ->warning('Изображение не удалено')
                ->push();
        }
    }

    public function updated($field)
    {
        $this->validateOnly($field, [
            'variations.*.unit_value' => 'required|numeric',
        ]);
    }

    public function sendDataToFrontend()
    {
        $this->dispatchBrowserEvent('set-product-brand', $this->productBrand);
        $this->dispatchBrowserEvent('set-brands', $this->brands);
        $this->dispatchBrowserEvent('set-ready-items', $this->att_selected);

        if ($this->productSerie) {
            $this->dispatchBrowserEvent(
                'set-product-serie',
                $this->productSerie
            );
        }
    }

    public function getCatalogs()
    {
        $this->catalogs = Catalog::with('categories')
            ->orderBy('name', 'ASC')
            ->get();
        $this->dispatchBrowserEvent('set-catalogs', $this->catalogs);
    }

    public function updateBrandsOnFrontend()
    {
        $this->brands = Brand::orderBy('name', 'ASC')->get();
        $this->dispatchBrowserEvent('update-brands', $this->brands);
    }

    public function getAttributes()
    {
        $this->attributes = Attribute::with('items')
            ->orderBy('name', 'ASC')
            ->get();
        $this->dispatchBrowserEvent('set-attributes', $this->attributes);
    }

    public function getBrandSeries($brand)
    {
        if ($brand && $brand[0]) {
            $this->series = BrandSerie::where('brand_id', $brand[0]['id'])
                ->orderBy('name', 'ASC')
                ->get();
            $this->dispatchBrowserEvent('set-series', $this->series);
        }
    }

    public function setVariation($id)
    {
        if ($this->checkArrayKey($this->variations, 'id', $id)) {
            toast()
                ->warning('Already added')
                ->push();
        } else {
            $this->variation = Product1C::where('id', $id)
                ->get()
                ->toArray();
            $this->variations = array_merge(
                $this->variations,
                $this->variation
            );
        }

        $this->setName($this->variation[0]['name']);

        if (! empty($this->variation[0]['description'])) {
            $this->setDescription($this->variation[0]['description']);
        }

        if (! empty($this->variation[0]['consist'])) {
            $this->setConsist($this->variation[0]['consist']);
        }
    }

    public function removeVariation($id)
    {
        foreach ($this->variations as $key => $item) {
            if ($item['id'] == $id) {
                unset($this->variations[$key]);

                toast()
                    ->warning('Removed')
                    ->push();
            }
        }
    }

    protected function checkArrayKey($array, $key, $value)
    {
        $results = [];

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge(
                    $results,
                    $this->checkArrayKey($subarray, $key, $value)
                );
            }
        }

        return $results;
    }

    public function save(
        $att_selected,
        $readyCategories,
        $productBrand,
        $productSerie
    ) {
        if (! $this->unitId) {
            $this->unitId = null;
        }

        $this->att_selected = $att_selected;
        $this->readyCategories = $readyCategories;
        $this->productBrand = $productBrand;
        $this->productSerie = $productSerie;

        // высчитывает среднее число для сортировки по цене
        $this->average = collect($this->variations)->avg('price');

        if (Str::of($this->description)->exactly('')) {
            $this->description = null;
        }

        if (Str::of($this->consist)->exactly('')) {
            $this->consist = null;
        }

        $this->validate([
            'name' => 'required',
            'status' => 'required',
            //'readyCategories' => 'required', // TODO test
            //'productBrand' => 'required', // TODO test
            //'meta_description' => 'max:150',
            //'meta_title' => 'max:70',
        ]);

        DB::transaction(function () {
            $functionProduct = Product::updateOrCreate(
                ['id' => $this->productId],
                [
                    'name' => trim($this->name),
                    'meta_title' => $this->meta_title,
                    'meta_description' => $this->meta_description,
                    'description' => $this->description,
                    'consist' => $this->consist,
                    'applying' => $this->applying,
                    'status' => $this->status,
                    'popularity' => 0,
                    'price_avg' => ceil($this->average),
                ]
            );

            $unit = ProductUnit::find($this->unitId);

            $functionProduct->unit()->associate($unit);

            if ($this->productBrand) {
                $functionProduct
                    ->brand()
                    ->associate($this->productBrand[0]['id'])
                    ->save();
            }

            if (! $this->productSerie == null) {
                $functionProduct
                    ->serie()
                    ->associate($this->productSerie)
                    ->save();
            } else {
                $functionProduct->update([
                    'brand_serie_id' => null,
                ]);
            }

            if (count($this->readyCategories) !== 0) {
                $functionProduct->categories()->detach();
                foreach ($this->readyCategories as $category) {
                    $functionProduct->categories()->attach($category['id']);
                }
            }

            if (count($this->att_selected) !== 0) {
                $functionProduct->attributes()->detach();
                foreach ($this->att_selected as $attribute) {
                    $functionProduct->attributes()->attach($attribute['id']);
                }
            }

            $functionProduct->variations()->update(['product_id' => null]);
            foreach ($this->variations as $value) {
                $product_1c = Product1C::find($value['id']);
                $functionProduct->variations()->save($product_1c);

                Str::replace(',', '.', $value['unit_value']);
                $product_1c->update([
                    'product_id' => $functionProduct->id,
                    'unit_value' => trim($value['unit_value']),
                ]);
            }
            $this->productId = $functionProduct->id;

            if ($this->photos) {
                foreach ($this->photos as $photo) {
                    $path = $photo->store('photos');
                    $img = \Image::make(storage_path('app/').$path);
                    $img->resize(800, 800, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    $img->save();

                    $this->product
                        ->addMedia(storage_path('app/').$path)
                        ->toMediaCollection('product-images');
                }

                $this->photos = [];
                $this->product = Product::where('id', $this->productId)
                    ->with('media')
                    ->first();
                $this->media = $this->product->getMedia('product-images');
            }

            $this->dispatchBrowserEvent('update-query-id', $this->productId);

            toast()
                ->success('Товар '.$functionProduct->name.' сохранен')
                ->push();
        });
    }

    public function removeImage($key)
    {
        $this->media[$key]->delete();

        $this->product = Product::find($this->product->id);
        $this->media = $this->product->getMedia('product-images');

        toast()
            ->success('Фото удалено')
            ->push();
    }

    public function setName($name)
    {
        if (empty($this->name)) {
            $this->name = $name;
        }

        if (empty($this->meta_title)) {
            $this->meta_title = $name;
        }
    }

    public function setDescription($description)
    {
        if (empty($this->description)) {
            $this->description = $description;
            $this->dispatchBrowserEvent('set-description', $this->description);
        }

        if (empty($this->meta_description)) {
            $this->meta_description = $description;
        }
    }

    public function setConsist($consist)
    {
        if (empty($this->consist)) {
            $this->consist = $consist;
            $this->dispatchBrowserEvent('set-consist', $this->consist);
        }
    }

    public function duplicate()
    {
        $this->productId = null;
        $this->name = null;
        $this->meta_title = null;
        $this->description = '';
        $this->meta_description = null;
        $this->consist = '';
        $this->variations = [];
        $this->dispatchBrowserEvent('set-description', $this->description);
        $this->dispatchBrowserEvent('set-consist', $this->consist);
    }

    public function destroy($id)
    {
        $functionProduct = Product::find($id);

        $functionProduct->delete();

        return redirect()->route('dashboard.products.index');
    }

    public function getProducts1C()
    {
        return Product1C::select('name', 'id', 'product_id', 'vendorcode')
            ->where('name', 'like', '%'.$this->search.'%')
            ->when($this->emptyStock, function ($query) {
                $query->where('stock', '>', 0);
            })
            ->when($this->taken, function ($query) {
                $query->whereNull('product_id');
            })
            ->orderBy('name', 'asc')
            ->paginate($this->itemsPerPage);
    }

    public function render()
    {
        return view('livewire.dashboard.products.edit', [
            'products_1c' => $this->getProducts1C(),
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
