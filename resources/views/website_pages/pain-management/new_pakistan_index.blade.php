@extends('layouts.new_pakistan_layout')

@section('meta_tags')
    @foreach ($meta_tags as $tags)
        <meta name="{{ $tags->name }}" content="{{ $tags->content }}">
    @endforeach
    <meta charset="utf-8" />
    <meta name="google-site-verification" content="Zgq0W54U_oOpntcqrKICmQpKyIPsJWhntAVoGqDCqV0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="language" content="en-us">
    <meta name="robots" content="index,follow" />
    <meta name="copyright" content="© 2022 All Rights Reserved. Powered By UmbrellaMd">
    <meta name="url" content="https://www.umbrellamd.com">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.umbrellamd.com" />
    <meta property="og:site_name" content="Community Healthcare Clinics | Umbrellamd.com" />
    <meta name="twitter:site" content="@umbrellamd	">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="author" content="Umbrellamd">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection


@section('page_title')
    @if ($title != null)
        <title>{{ $title->content }}</title>
    @else
        <title>Pain Management | Community Healthcare Clinics</title>
    @endif
@endsection

@section('top_import_file')
@endsection


@section('bottom_import_file')
@endsection

@section('content')
    <main>
        <div class="contact-section">
            <div class="contact-content">
                <h1>Pain Management</h1>
                <div class="underline3"></div>
            </div>
            <div class="custom-shape-divider-bottom-17311915372">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                    preserveAspectRatio="none">
                    <path
                        d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                        opacity=".25" class="shape-fill"></path>
                    <path
                        d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                        opacity=".5" class="shape-fill"></path>
                    <path
                        d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                        class="shape-fill"></path>
                </svg>
            </div>
        </div>

        <div class="container">
            <h3>Community Healthcare Clinics - Pain Management</h3>
        </div>

        <div class="container my-3 z-3 pharmacy-page-container">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link p-3 active" id="Cancer-Related-Pain-tab" data-bs-toggle="tab"
                        data-bs-target="#Cancer-Related-Pain-tab-pane" type="button" role="tab"
                        aria-controls="Cancer-Related-Pain-tab-pane" aria-selected="true">Cancer-Related Pain</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link p-3" id="Joint-And-Muscle-Pain-tab" data-bs-toggle="tab"
                        data-bs-target="#Joint-And-Muscle-Pain-tab-pane" type="button" role="tab"
                        aria-controls="Joint-And-Muscle-Pain-tab-pane" aria-selected="false">Joint-And-Muscle-Pain</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link p-3" id="Neck-And-Back-Pain-tab" data-bs-toggle="tab"
                        data-bs-target="#Neck-And-Back-Pain-tab-pane" type="button" role="tab"
                        aria-controls="Neck-And-Back-Pain-tab-pane" aria-selected="false">Neck-And-Back-Pain</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link p-3" id="Nerve-Related-Pain-tab" data-bs-toggle="tab"
                        data-bs-target="#Nerve-Related-Pain-tab-pane" type="button" role="tab"
                        aria-controls="Nerve-Related-Pain-tab-pane" aria-selected="false">Nerve-Related-Pain</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="Cancer-Related-Pain-tab-pane" role="tabpanel"
                    aria-labelledby="Cancer-Related-Pain-tab" tabindex="0">
                    <h6>
                        Pain can result from cancer or its treatments — even after treatment, in some cases. If you have
                        pain during or after active treatment, we can help:
                    </h6>
                    <p>Relieve pain from cancer itself, especially if the cancer is advanced</p>
                    <p>Relieve pain due to cancer treatments, such as surgery, radiation therapy or chemotherapy</p>
                    <p>Manage chronic pain after cancer</p>
                </div>
                <div class="tab-pane fade" id="Joint-And-Muscle-Pain-tab-pane" role="tabpanel"
                    aria-labelledby="Joint-And-Muscle-Pain-tab-pane" tabindex="0">
                <div>
                    <h2>Joint And Muscle Pain</h2>
                    <p>
                        Musculoskeletal pain affects bones, muscles and connective tissue such as cartilage, ligaments and
                        tendons. The most common causes of this pain are arthritis and injury. Musculoskeletal pain that we
                        treat includes:
                    </p>
                </div>
                <div>
                    <h6>
                        Joint pain:
                    </h6>
                    <p>
                        Inflammation (pain and swelling) in major joints such as the hip, knee and shoulder
                    </p>
                </div>
                <div>
                    <h6>
                        Malignant compression fractures:
                    </h6>
                    <p>
                        Fractures in vertebrae that result from cancerous tumors, usually metastatic (tumors that start in
                        another part of the body and spread to the vertebrae)
                    </p>
                </div>
                <div>
                    <h6>
                        Benign compression fractures:
                    </h6>
                    <p>
                        Vertebral fractures typically due to osteoporosis, a condition that causes bones to become weak and
                        brittle
                    </p>
                </div>
                <div>
                    <h6>
                        Sacroiliac (SI) joint pain:
                    </h6>
                    <p>
                        Pain in the low back and buttocks due to arthritis, injury or another problem with the SI joint
                    </p>
                </div>
            </div>
            <div class="tab-pane fade" id="Neck-And-Back-Pain-tab-pane" role="tabpanel"
                aria-labelledby="Neck-And-Back-Pain-tab-pane" tabindex="0">
                <div>
                    <h2>Neck And Back Pain</h2>
                </div>
                <div>
                    <h6>
                        One of the most common causes of chronic pain is back and neck pain. Sharp pain or dull aches can
                        result from injury, strain, poor posture, wear and tear or many other reasons. We treat pain all
                        along the spine, from the top of your neck to your tailbone,including:
                    </h6>
                    <p><b>Degenerative disc disease: </b>Vertebrae (spine bones) that rub together as the discs (rubbery
                        tissue between vertebrae) wear out due to age or injury</p>
                    <P><b>Facet joint syndrome: </b>Damage to facet joints (connections between vertebrae) due to injury,
                        disc deterioration or wear and tear</P>
                    <P><b>Failed back syndrome: </b>Back pain that returns after surgery</P>
                    <P><b>Herniated disc: </b>A disc that breaks open (ruptures) or moves out of place due to injury or
                        strenuous activity</P>
                    <P><b>Radiculopathy (pinched nerve): </b>A damaged or compressed nerve root in the spine, causing pain
                        and other symptoms in various areas of the body depending on where it develops</P>
                    <p><b>Sciatica: </b> Radiculopathy that develops in the low back</p>
                    <p><b>Spinal stenosis: </b>A narrowed area of the spinal canal that causes pain due to compression of
                        the spinal cord or nerve roots</p>
                </div>
            </div>
            <div class="tab-pane fade" id="Nerve-Related-Pain-tab-pane" role="tabpanel"
                aria-labelledby="Nerve-Related-Pain-tab-pane" tabindex="0">
                <div>
                    <h2>Nerve-Related Pain</h2>
                </div>
                <div>
                    <h6>
                        Neuropathy, also called neuralgia, is nerve damage that causes pain, tingling, numbness and muscle
                        weakness. Nerve-related pain can result from injury or conditions such as diabetes and
                        atherosclerosis. Some common types of neuropathy include:
                    </h6>
                    <p><b>Complex regional pain syndrome:</b>Chronic pain, usually in one limb, due to a problem with pain
                        nerves that sometimes develops after an injury</p>
                    <p><b>Peripheral neuropathy: </b>Neuropathy that affects nerves outside the brain and spinal cord, most
                        commonly in the legs and feet; includes diabetic neuropathy</p>
                    <p><b>Postherpetic neuralgia: </b>Nerve damage that develops after shingles, a viral infection that
                        causes a blistering rash, usually on one side of the torso </p>
                </div>
            </div>
        </div>
        </div>
    </main>
@endsection
