// app/Http/Controllers/SparePartController.php

use App\Models\SparePart;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    public function index(Request $request)
    {
        $spareParts = SparePart::query();

        if ($request->has('search')) {
            $spareParts->where('name', 'like', '%' . $request->search . '%');
        }

        $spareParts = $spareParts->paginate(10);

        return view('spare-parts.index', compact('spareParts'));
    }

    public function create()
    {
        return view('spare-parts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            // Add validation for other fields as needed
        ]);

        SparePart::create($request->all());

        return redirect()->route('spare-parts.index')->with('success', 'Spare part created successfully!');
    }

    public function show(SparePart $sparePart)
    {
        return view('spare-parts.show', compact('sparePart'));
    }

    public function edit(SparePart $sparePart)
    {
        return view('spare-parts.edit', compact('sparePart'));
    }

    public function update(Request $request, SparePart $sparePart)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            // Add validation for other fields as needed
        ]);

        $sparePart->update($request->all());

        return redirect()->route('spare-parts.index')->with('success', 'Spare part updated successfully!');
    }

    public function destroy(SparePart $sparePart)
    {
        $sparePart->delete();

        return redirect()->route('spare-parts.index')->with('success', 'Spare part deleted successfully!');
    }

    public function report()
    {
        $sparePartsByCategory = SparePart::groupBy('category')->selectRaw('category, count(*) as total')->pluck('total', 'category');
        $totalStock = SparePart::sum('stock');
        $stockPercentages = SparePart::selectRaw('name, (stock / ? * 100) as percentage', [$totalStock])->pluck('percentage', 'name');

        return view('spare-parts.report', compact('sparePartsByCategory', 'stockPercentages'));
    }
}