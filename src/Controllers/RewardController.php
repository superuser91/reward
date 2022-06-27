<?php

namespace Vgplay\Reward\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Vgplay\LaravelRedisModel\Contracts\Cacheable;
use Vgplay\Reward\Actions\ProductCreater;
use Vgplay\Reward\Actions\ProductUpdater;
use Vgplay\Reward\Models\Product;
use Vgplay\Reward\Models\Shop;

class RewardController
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $rewards = Product::all();

        return view('vgplay::rewards.index', compact('rewards'));
    }

    public function show(Request $request, $id)
    {
        return redirect(route('rewards.edit', $id));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Product::class);

        $types = config('vgplay.products.rewardables');
        $paymentUnits = config('vgplay.products.payment_units');

        $rewardables = collect([]);
        foreach ($types as $type) {
            if (in_array(Cacheable::class, class_implements($type))) {
                $rewardables->push(...$type::fromCache()->all());
            } elseif (in_array(Model::class, class_implements($type))) {
                $rewardables->push(...$type::all());
            }
        }

        $shops = Shop::fromCache()->all();

        return view('vgplay::rewards.create', compact('rewardables', 'shops', 'paymentUnits'));
    }

    public function store(Request $request, ProductCreater $creater)
    {
        $this->authorize('create', Product::class);

        try {
            $creater->create($request->all());
            session()->flash('status', 'Thêm thành công');

            return redirect(route('rewards.index'));
        } catch (ValidationException $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $rewardables = collect();

        $reward = Product::findOrFail($id);

        $this->authorize('update', $reward);

        $paymentUnits = config('vgplay.products.payment_units');

        return view('vgplay::rewards.edit', compact('reward', 'rewardables', 'paymentUnits'));
    }

    public function update(Request $request, ProductUpdater $updater, $id)
    {
        $reward = Product::findOrFail($id);

        $this->authorize('update', $reward);

        try {
            $updater->update($reward, $request->all());

            session()->flash('status', 'Cập nhật thành công');

            return redirect(route('rewards.index'));
        } catch (ValidationException $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        $reward = Product::findOrFail($id);

        $this->authorize('delete', $reward);

        try {
            $reward->delete();
            session()->flash('status', 'Xoá thành công');
            return redirect(route('rewards.index'));
        } catch (\Exception $e) {
            session()->flash('status', $e->getMessage());
            return back();
        }
    }
}
