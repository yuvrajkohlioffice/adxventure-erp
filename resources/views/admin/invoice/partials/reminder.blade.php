 <div class="modal" id="whatshappModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reminder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form data-action="{{ route('reminder.send') }}" data-method="POST" class="ajax-form"> 
                    @csrf
                    <div class="row">
                        <input type="hidden" id="TemplateSendId" name="TemplateSendId">
                        <!-- <div class="col-12">
                                <label for="templateSelect" class="form-label">Select Template</label>
                                <select name="type" id="templateSelect" class="form-select" onchange="handleTemplateChange(this.value)">
                                    <option selected disabled>Choose Template</option>
                                    <option value="custom">Custom</option>
                                    <option value="common">Common</option>
                                </select>
                            </div> -->

                            <div class="col-12" id="templateType">
                                <label for="templateTypeSelect" class="form-label">Select Type</label>
                                <select name="template" class="form-select" id="templateTypeSelect">    
                                    @if(isset($templates))
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->title }}</option>
                                    @endforeach 
                                    @endif
                                </select>
                            </div>

                            <!-- <div class="col-12 mt-3" id="templateMessage" style="display:none">
                                <label for="message" class="form-label">Message</label>
                                <textarea name="message" id="message" class="form-control"></textarea>
                            </div> -->

                            <div class="col-12 mt-3">
                                <label for="message" class="form-label">Invoice Image & Pdf</label>
                                <input type="file" name="file" class="form-control">
                            </div>
                        <div class="col-3 mt-3">
                            <button type="submit" class="btn btn-success">Send</button>
                        </div>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>