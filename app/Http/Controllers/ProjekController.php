<?php    
    
namespace App\Http\Controllers;    
    
use App\Models\projek;    
use App\Http\Requests\StoreprojekRequest;    
use App\Http\Requests\UpdateprojekRequest;   
use App\Models\Pic;
use App\Models\Mitra; 
    
class ProjekController extends Controller    
{    
    /**    
     * Display a listing of the resource.    
     *    
     * @return \Illuminate\Http\Response    
     */    
    public function index()    
    {    
        // Query data    
        $projek = projek::all();    
        return view('projek.view', [    
            'projek' => $projek    
        ]);    
    }    
    
    /**    
     * Show the form for creating a new resource.    
     *    
     * @return \Illuminate\Http\Response    
     */    
    public function create()    
    {    
        return view('projek.create', [    
            'kode_projek' => projek::getKodeProjek(), // Menghasilkan kode projek baru
            'pics' => Pic::all(),
            'mitras' => Mitra::all()    
        ]);    
    }    
    
    /**    
     * Store a newly created resource in storage.    
     *    
     * @param  \App\Http\Requests\StoreprojekRequest  $request    
     * @return \Illuminate\Http\Response    
     */    
    public function store(StoreprojekRequest $request)    
    {    
        // Validasi    
        // $validated = $request->validate([    
        //     'kode_projek' => 'required|unique:projek,kode_projek',    
        //     'nama_projek' => 'required|min:5|max:255',    
        //     'tanggal_mulai' => 'required',    
        //     'tanggal_selesai' => 'required',    
        //     'besar_anggaran' => 'required',    
        // ]);    

        $validated = $request->validate([
            'kode_projek' => 'required|unique:projek,kode_projek',
            'nama_projek' => 'required|min:5|max:255',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'besar_anggaran',
            'pic_id' => 'nullable|exists:pic,id',
            'mitra_id' => 'nullable|exists:mitra,id',
        ]);
    
        // Debugging: Tampilkan kode_projek yang dihasilkan    
        \Log::info('Kode Projek: ' . $validated['kode_projek']);    
    
        // Masukkan ke db    
        projek::create($validated);    
            
        return redirect()->route('projek.index')->with('success', 'Data Berhasil di Input');    
    }    
    
    /**    
     * Display the specified resource.    
     *    
     * @param  \App\Models\projek  $projek    
     * @return \Illuminate\Http\Response    
     */    
    public function show(projek $projek)    
    {    
        //    
    }    
    
    /**    
     * Show the form for editing the specified resource.    
     *    
     * @param  \App\Models\projek  $projek    
     * @return \Illuminate\Http\Response    
     */    
    // public function edit(projek $projek)    
    // {    
    //     return view('projek.edit', compact('projek'));    
    // }    
    public function edit(projek $projek)
    {
        return view('projek.edit', [
            'projek' => $projek,
            'pics' => Pic::all(),
            'mitras' => Mitra::all()
        ]);
    }
    
    /**    
     * Update the specified resource in storage.    
     *    
     * @param  \App\Http\Requests\UpdateprojekRequest  $request    
     * @param  \App\Models\projek  $projek    
     * @return \Illuminate\Http\Response    
     */    
    public function update(UpdateprojekRequest $request, projek $projek)    
{    
    // Validasi    
    // $validated = $request->validate([    
    //     'kode_projek' => 'required|unique:projek,kode_projek,' . $projek->id, // Mengabaikan entri yang sedang diperbarui    
    //     'nama_projek' => 'required|max:255',    
    //     'tanggal_mulai' => 'required',    
    //     'tanggal_selesai' => 'required',    
    //     'besar_anggaran' => 'required',    
    // ]); 
    
    $validated = $request->validate([
        'kode_projek' => 'required|unique:projek,kode_projek,' . $projek->id,
        'nama_projek' => 'required|max:255',
        'tanggal_mulai' => 'required',
        'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        'besar_anggaran',
        'pic_id' => 'nullable|exists:pic,id',
        'mitra_id' => 'nullable|exists:mitra,id',
    ]);
  
    // Tambahkan log untuk memeriksa nilai kode_projek yang divalidasi  
    \Log::info('Validating Kode Projek: ' . $validated['kode_projek']);    
  
    // Update data    
    $projek->update($validated);    
    
    return redirect()->route('projek.index')->with('success', 'Data Berhasil di Ubah');    
}  
  
    
    /**    
     * Remove the specified resource from storage.    
     *    
     * @param  \App\Models\projek  $projek    
     * @return \Illuminate\Http\Response    
     */    
    public function destroy($id)    
    {    
        // Hapus dari database    
        $projek = projek::findOrFail($id);    
        $projek->delete();    
    
        return redirect()->route('projek.index')->with('success', 'Data Berhasil di Hapus');    
    }    
}    
