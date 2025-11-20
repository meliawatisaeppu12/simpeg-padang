<x-admin>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl sm:rounded-lg">
                <div class="pd-ltr-20">
                    <div class="card-box pd-20 height-100-p mb-30">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <img src="{{url('vendor/images/banner-img.png')}}" alt="">
                            </div>
                            <div class="col-md-8">
                                <h4 class="font-20 weight-500 mb-10 text-capitalize">
                                    Selamat Datang <div class="weight-600 font-30 text-black">{{Auth::user()->v2Profile->nama.', '.Auth::user()->v2Profile->gelar_belakang}} !</div>
                                </h4>
                                <p class="font-18 max-width-600">Kelola data kepegawaian menggunakan Sistem Informasi Kepegawaian (SIMPEG) Kota Padang.</p>
                            </div>
                        </div>
                    </div>
                    @if($hak_akses)
                        <div class="row">
                            <div class="col-xl-3 mb-30">
                                <div class="card-box height-100-p widget-style1">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="progress-data">
                                            <div id="chart-struktural"></div>
                                        </div>
                                        <div class="widget-data">
                                            <div class="h4 mb-0">{{$jumlah_struktural}}</div>
                                            <div class="weight-600 font-14">Pejabat Struktural</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 mb-30">
                                <div class="card-box height-100-p widget-style1">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="progress-data">
                                            <div id="chart-fungsional"></div>
                                        </div>
                                        <div class="widget-data">
                                            <div class="h4 mb-0">{{$jumlah_fungsional}}</div>
                                            <div class="weight-600 font-14">Fungsional</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 mb-30">
                                <div class="card-box height-100-p widget-style1">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="progress-data">
                                            <div id="chart-nonaktif"></div>
                                        </div>
                                        <div class="widget-data">
                                            <div class="h4 mb-0">{{$jumlah_nonaktif}}</div>
                                            <div class="weight-600 font-14">Pegawai Nonaktif</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 mb-30">
                                <div class="card-box height-100-p widget-style1">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <div class="progress-data">
                                            <div id="chart-aktif"></div>
                                        </div>
                                        <div class="widget-data">
                                            <div class="h4 mb-0">{{$jumlah_aktif}}</div>
                                            <div class="weight-600 font-14">Pegawai Aktif</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <a class="w-100 btn btn-info btn-sm" href="{{route('data-pegawai')}}">Lihat Data Pegawai <i class="fa fa-arrow-right"></i></a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@section('scripts')
    <script src="{{url('src/plugins/apexcharts/apexcharts.min.js')}}"></script>
	<script src="{{url('src/plugins/datatables/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{url('src/plugins/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{url('src/plugins/datatables/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{url('src/plugins/datatables/js/responsive.bootstrap4.min.js')}}"></script>
	<script src="{{url('vendor/scripts/dashboard.js')}}"></script>
    @if($hak_akses)
        <script type="text/javascript">

            var optionss = {
                series: ['{{$persentase_struktural}}'],
                grid: {
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                chart: {
                    height: 100,
                    width: 70,
                    type: 'radialBar',
                },	
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '50%',
                        },
                        dataLabels: {
                            name: {
                                show: false,
                                color: '#fff'
                            },
                            value: {
                                show: true,
                                color: '#333',
                                offsetY: 5,
                                fontSize: '15px'
                            }
                        }
                    }
                },
                colors: ['#ecf0f4'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'diagonal1',
                        shadeIntensity: 0.8,
                        gradientToColors: ['#1b00ff'],
                        inverseColors: false,
                        opacityFrom: [1, 0.2],
                        opacityTo: 1,
                        stops: [0, 100],
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                    active: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                }
            };

            var optionsf = {
                series: ['{{$persentase_fungsional}}'],
                grid: {
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                chart: {
                    height: 100,
                    width: 70,
                    type: 'radialBar',
                },	
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '50%',
                        },
                        dataLabels: {
                            name: {
                                show: false,
                                color: '#fff'
                            },
                            value: {
                                show: true,
                                color: '#333',
                                offsetY: 5,
                                fontSize: '15px'
                            }
                        }
                    }
                },
                colors: ['#ecf0f4'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'diagonal1',
                        shadeIntensity: 0.8,
                        gradientToColors: ['#009688'],
                        inverseColors: false,
                        opacityFrom: [1, 0.2],
                        opacityTo: 1,
                        stops: [0, 100],
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                    active: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                }
            };

            var optionsn = {
                series: ['{{$persentase_nonaktif}}'],
                grid: {
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                chart: {
                    height: 100,
                    width: 70,
                    type: 'radialBar',
                },	
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '50%',
                        },
                        dataLabels: {
                            name: {
                                show: false,
                                color: '#fff'
                            },
                            value: {
                                show: true,
                                color: '#333',
                                offsetY: 5,
                                fontSize: '15px'
                            }
                        }
                    }
                },
                colors: ['#ecf0f4'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'diagonal1',
                        shadeIntensity: 0.8,
                        gradientToColors: ['#f56767'],
                        inverseColors: false,
                        opacityFrom: [1, 0.2],
                        opacityTo: 1,
                        stops: [0, 100],
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                    active: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                }
            };

            var optionsa = {
                series: ['{{$persentase_aktif}}'],
                grid: {
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                chart: {
                    height: 100,
                    width: 70,
                    type: 'radialBar',
                },	
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '50%',
                        },
                        dataLabels: {
                            name: {
                                show: false,
                                color: '#fff'
                            },
                            value: {
                                show: true,
                                color: '#333',
                                offsetY: 5,
                                fontSize: '15px'
                            }
                        }
                    }
                },
                colors: ['#ecf0f4'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'diagonal1',
                        shadeIntensity: 0.8,
                        gradientToColors: ['#2979ff'],
                        inverseColors: false,
                        opacityFrom: [1, 0.2],
                        opacityTo: 1,
                        stops: [0, 100],
                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                    active: {
                        filter: {
                            type: 'none',
                            value: 0,
                        }
                    },
                }
            };

            var charts = new ApexCharts(document.querySelector("#chart-struktural"), optionss);
            charts.render();

            var chartf = new ApexCharts(document.querySelector("#chart-fungsional"), optionsf);
            chartf.render();

            var chartn = new ApexCharts(document.querySelector("#chart-nonaktif"), optionsn);
            chartn.render();

            var charta = new ApexCharts(document.querySelector("#chart-aktif"), optionsa);
            charta.render();
        </script>
    @endif
@endsection
</x-admin>