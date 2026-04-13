<?php require APPROOT . '/views/coachdash/inc/header.php'; ?>
<div class="kal-coach-profile-manager">
    <div class="kal-coach-profile-header">
        <h1>Edit Profile</h1>
        <button form="editProfileForm" class="kal-profile-save-btn">Save Changes</button>
    </div>

    <form id="editProfileForm" action="<?php echo URLROOT; ?>/coachdash/updateProfile" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($data['coach']['id']); ?>">
        <div class="kal-profile-layout">
            <div class="kal-profile-sidebar">
                <div class="kal-profile-card">









                    <h3>Profile Photo</h3>
                    <div class="kal-profile-photo">
                        <div class="kal-profile-photo-img">
                            <?php if(!empty($data['coach']['image'])): ?>
                                <button type="button" class="kal-photo-remove-btn" onclick="removeProfilePhoto()">×</button>
                                <img src="<?php echo URLROOT . '/' . $data['coach']['image']; ?>" alt="<?php echo htmlspecialchars($data['coach']['name']); ?>">
                            <?php else: ?>
                                <span>No Photo</span>
                            <?php endif; ?>
                        </div>
                        <div class="kal-photo-upload">
                            <input type="file" id="profilePhoto" name="profile_photo" accept="image/*" style="display: none;">
                            <button type="button" class="kal-upload-btn" onclick="document.getElementById('profilePhoto').click()">
                                Upload New Photo
                            </button>
                            <small style="color: #888; text-align: center;">JPG, PNG max 5MB</small>
                        </div>
                    </div>
                </div>

                <!-- Availability Card -->
                <div class="kal-profile-card">
                    <h3>Availability</h3>
                    <div class="kal-form-group">
                        <label for="current_status">Current Status</label>
                        <select class="kal-form-control" id="current_status" name="current_status">
                            <option value="available" <?php echo ($data['coach']['availability_text'] ?? '') === 'available' ? 'selected' : ''; ?>>Available</option>
                            <option value="unavailable" <?php echo ($data['coach']['availability_text'] ?? '') === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                            <option value="flexibility" <?php echo ($data['coach']['availability_text'] ?? '') === 'flexibility' ? 'selected' : ''; ?>>Flexibility</option>
                        </select>
                    </div>
                    <div class="kal-form-group">
                        <label for="hourly_rate">Hourly Rate (LKR)</label>
                        <input type="number" class="kal-form-control" id="hourly_rate" name="hourly_rate" value="<?php echo htmlspecialchars($data['coach']['hourly_rate'] ?? ''); ?>" min="0">
                    </div>
                </div>
            </div>











            <div class="kal-main-content">
                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>Basic Information</h3>
                    </div>
                    <div class="kal-form-grid">
                        <div class="kal-form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="kal-form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($data['coach']['first_name'] ?? (explode(' ', $data['coach']['name'] ?? '')[0] ?? '')); ?>">
                        </div>
                        <div class="kal-form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="kal-form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($data['coach']['last_name'] ?? (explode(' ', $data['coach']['name'] ?? '')[1] ?? '')); ?>">
                        </div>





                        <div class="kal-form-group">
                            <label for="mobile">Mobile Number</label>
                            <input type="tel" class="kal-form-control" id="mobile" name="mobile" maxlength="15" value="<?php echo htmlspecialchars($data['coach']['mobile'] ?? ''); ?>">
                            <?php if (!empty($data['errors']['mobile'])): ?>
                                <div class="kal-field-error" style="color:#c0392b; margin-top:6px; font-size:13px;">
                                    <?php echo htmlspecialchars($data['errors']['mobile']); ?>
                                </div>
                            <?php endif; ?>
                            <div id="mobileClientError" style="display:none;color:#c0392b;margin-top:6px;font-size:13px;"></div>
                        </div>





                        <div class="kal-form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="kal-form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['coach']['email'] ?? ''); ?>">
                        </div>
                        <!-- Primary sport selection left in form (handled elsewhere) -->
                        <div class="kal-form-group">
                            <label for="district">District</label>
                            <select class="kal-form-control" id="district" name="district">
                                <?php
                                // Use cities provided by controller (from M_Register::getCities()).
                                // Fallback to a short default list if not provided.
                                $districts = $data['cities'] ?? ['Colombo','Gampaha','Kandy','Galle','Matara','Jaffna','Kegalle','Kurunegala'];
                                $currentLocation = $data['coach']['location'] ?? $data['coach']['district'] ?? '';
                                foreach ($districts as $k => $dist) {
                                    // $districts may be an associative array (key => label) or a simple indexed list
                                    if (is_int($k)) {
                                        $value = $dist;
                                        $label = $dist;
                                    } else {
                                        $value = $k;
                                        $label = $dist;
                                    }
                                    $sel = ($currentLocation === $value) ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($value) . "\" $sel>" . htmlspecialchars($label) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="kal-form-group">
                            <label for="availability">Primary Availability</label>
                            <select class="kal-form-control" id="availability" name="availability">
                                <option value="full_time" <?php echo ($data['coach']['availability'] ?? '') === 'full_time' ? 'selected' : ''; ?>>Full Time</option>
                                <option value="part_time" <?php echo ($data['coach']['availability'] ?? '') === 'part_time' ? 'selected' : ''; ?>>Part Time</option>
                                <option value="weekdays" <?php echo ($data['coach']['availability'] ?? '') === 'weekdays' ? 'selected' : ''; ?>>Weekdays</option>
                                <option value="weekends" <?php echo ($data['coach']['availability'] ?? '') === 'weekends' ? 'selected' : ''; ?>>Weekends</option>
                                <option value="flexible" <?php echo ($data['coach']['availability'] ?? '') === 'flexible' ? 'selected' : ''; ?>>Flexible</option>
                            </select>
                        </div>







                        
                        <div class="kal-form-group">
                            <label for="specialization">Sports Specialization</label>
                            <select class="kal-form-control" id="specialization" name="specialization">
                                <option value="">-- Select Primary Sport --</option>
                                <?php
                                // $data['sports'] expected to be an array like ['Football','Cricket',...]
                                $currentPrimary = '';
                                if (!empty($data['coach']['specialization'])) {
                                    if (is_array($data['coach']['specialization'])) {
                                        $currentPrimary = $data['coach']['specialization'][0] ?? '';
                                    } else {
                                        $currentPrimary = (string)$data['coach']['specialization'];
                                    }
                                }
                                foreach ($data['sports'] as $key => $label) {
                                    // sports list from M_Register uses key => label (e.g. 'football' => 'Football')
                                    $sel = ($currentPrimary === $key) ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($key)."\" $sel>".htmlspecialchars($label)."</option>";
                                }
                                ?>
                            </select>
                        </div>








                        <div class="kal-form-group">
                            <label for="certification">Certification</label>
                            <select class="kal-form-control" id="certification" name="certification">
                                <?php
                                $certs = ['none','basic','intermediate','advanced','professional','international'];
                                foreach($certs as $c){
                                    $sel = ($data['coach']['certification'] ?? '') === $c ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($c)."\" $sel>".htmlspecialchars(ucfirst($c))."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="kal-form-group">
                            <label for="experience">Experience</label>
                            <select class="kal-form-control" id="experience" name="experience">
                                <?php
                                $exps = ['1_3','4_6','7_10','11_15','15_plus'];
                                foreach($exps as $e){
                                    $label = $e === '15_plus' ? '15+ years' : str_replace('_','-',$e) . ' years';
                                    $sel = ($data['coach']['experience'] ?? '') === $e ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($e)."\" $sel>".htmlspecialchars($label)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="kal-form-group">
                            <label for="coaching_type">Coaching Type</label>
                            <select class="kal-form-control" id="coaching_type" name="coaching_type">
                                <?php
                                $types = ['individual','group','team','both','all'];
                                foreach($types as $t){
                                    $sel = ($data['coach']['coaching_type'] ?? '') === $t ? 'selected' : '';
                                    echo "<option value=\"".htmlspecialchars($t)."\" $sel>".htmlspecialchars(ucfirst($t))."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>About Me</h3>
                    </div>
                    <div class="kal-form-group full-width">
                        <label for="bio">Bio / Description</label>
                        <textarea class="kal-form-control" id="bio" name="bio" rows="5"><?php echo htmlspecialchars($data['coach']['bio'] ?? ''); ?></textarea>
                    </div>
                    <div class="kal-form-group full-width">
                        <label for="training_style">Training Style & Philosophy</label>
                        <textarea class="kal-form-control" id="training_style" name="training_style" rows="4"><?php echo htmlspecialchars($data['coach']['training_style'] ?? $data['coach']['training_style'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>Languages Spoken</h3>
                    </div>
                    <div class="kal-tags-container" id="languagesContainer">
                        <?php foreach($data['coach']['languages'] as $lang): ?>
                            <div class="kal-tag">
                                <?php echo htmlspecialchars($lang); ?>
                                <button type="button" class="kal-tag-remove" onclick="removeTag(this)">×</button>
                                <input type="hidden" name="languages[]" value="<?php echo htmlspecialchars($lang); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="kal-add-tag">
                        <input type="text" class="kal-form-control" id="newLanguage" placeholder="Add new language">
                        <button type="button" class="kal-edit-btn" onclick="addLanguage()">Add</button>
                    </div>
                </div>





                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>Free Training Sessions</h3>
                    </div>
                    <div class="kal-slot-grid" id="freeSlotsContainer">
                        <?php foreach($data['coach']['free_slots'] as $index => $slot): ?>
                            <div class="kal-slot-card">
                                <button type="button" class="kal-slot-remove" onclick="removeSlot(this)">×</button>
                                <div class="kal-slot-day"><?php echo htmlspecialchars($slot->day); ?></div>
                                <div class="kal-slot-time"><?php echo htmlspecialchars($slot->time_slot ?? $slot->time); ?></div>
                                <div class="kal-slot-type"><?php echo htmlspecialchars($slot->session_type ?? $slot->type); ?></div>
                                <button type="button" class="kal-slot-edit" onclick="editSlot(this)">Edit</button>
                                <input type="hidden" name="free_slots[<?php echo $index; ?>][id]" value="<?php echo ($slot->id ?? ''); ?>">
                                <input type="hidden" name="free_slots[<?php echo $index; ?>][day]" value="<?php echo htmlspecialchars($slot->day); ?>">
                                <input type="hidden" name="free_slots[<?php echo $index; ?>][time]" value="<?php echo htmlspecialchars($slot->time_slot ?? $slot->time); ?>">
                                <input type="hidden" name="free_slots[<?php echo $index; ?>][type]" value="<?php echo htmlspecialchars($slot->session_type ?? $slot->type); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>







                    <!--Hidden container to hide delete slots-->
                    <div id="deletedSlotsContainer"></div>






                    <div class="kal-add-slot-form">
                        <div class="kal-form-grid">
                            <div class="kal-form-group">
                                <label for="newSlotDay">Day</label>
                                <select class="kal-form-control" id="newSlotDay">
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                            <div class="kal-form-group">
                                <label for="newSlotTime">Time Slot</label>
                                <input type="text" class="kal-form-control" id="newSlotTime" placeholder="e.g., 4:00 PM - 5:00 PM">
                            </div>
                            <div class="kal-form-group">
                                <label for="newSlotType">Session Type</label>
                                <input type="text" class="kal-form-control" id="newSlotType" placeholder="e.g., Group Session">
                            </div>
                        </div>
                        <button type="button" class="kal-edit-btn" onclick="addFreeSlot()" style="margin-top: 10px;">Add Slot</button>
                    </div>
                </div>

                <div class="kal-profile-section">
                    <div class="kal-section-header">
                        <h3>Achievements & Awards</h3>
                    </div>
                    <ul class="kal-achievement-list" id="achievementsList">
                        <?php foreach($data['coach']['achievements'] as $index => $achievement): ?>
                            <li class="kal-achievement-item">
                                <span class="kal-achievement-text"><?php echo htmlspecialchars($achievement); ?></span>
                                <button type="button" class="kal-remove-btn" onclick="removeAchievement(this)">Remove</button>
                                <input type="hidden" name="achievements[]" value="<?php echo htmlspecialchars($achievement); ?>">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="kal-add-achievement">
                        <input type="text" class="kal-form-control" id="newAchievement" placeholder="Add new achievement">
                        <button type="button" class="kal-edit-btn" onclick="addAchievement()">Add</button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/coachdash/inc/footer.php'; ?>





<script>
//remove profile photo
function removeProfilePhoto() {
    if (confirm('Remove profile photo?')) {
        const container = document.querySelector('.kal-profile-photo-img');
        container.innerHTML = '<span>No Photo</span>';

        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_photo';
        input.value = '1';

        document.getElementById('editProfileForm').appendChild(input);
    }
}








// Languages tag helper for edit page
function removeTag(button) {
    // remove the tag element
    const el = button.closest('.kal-tag');
    if (el) el.remove();
}

function addLanguage() {
    const input = document.getElementById('newLanguage');
    const value = (input.value || '').trim();
    if (!value) return;

    // prevent duplicates
    const existing = Array.from(document.querySelectorAll('#languagesContainer .kal-tag input[name="languages[]"]'))
        .map(i => i.value.toLowerCase());
    if (existing.includes(value.toLowerCase())) {
        input.value = '';
        return;
    }

    const container = document.getElementById('languagesContainer');
    const div = document.createElement('div');
    div.className = 'kal-tag';
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'kal-tag-remove';
    btn.textContent = '×';
    btn.onclick = function() { removeTag(this); };

    const text = document.createTextNode(value);
    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'languages[]';
    hidden.value = value;

    div.appendChild(text);
    div.appendChild(btn);
    div.appendChild(hidden);
    container.appendChild(div);

    input.value = '';
    input.focus();
}
/*This is just a wrapper that says:
“Run everything inside after the page loads”
*/
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('editProfileForm');
    var mobileInput = document.getElementById('mobile');
    var clientError = document.getElementById('mobileClientError');
    var profilePhotoInput = document.getElementById('profilePhoto');
    
    // Client-side validation for mobile number: ensure exactly 10 digits
    function validateMobile() {
        if (!mobileInput) return true;
        var digits = (mobileInput.value || '').replace(/\D/g, '');
        if (digits.length !== 10) {
            if (clientError) {
                clientError.textContent = 'Mobile number must contain exactly 10 digits.';
                clientError.style.display = 'block';
            }
            mobileInput.classList.add('field-error');
            return false;
        }
        if (clientError) {
            clientError.textContent = '';
            clientError.style.display = 'none';
        }
        mobileInput.classList.remove('field-error');
        return true;
    }

    // Photo preview on file selection (before form submit)
    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var photoImg = document.querySelector('.kal-profile-photo-img');
                    if (photoImg) {
                        photoImg.innerHTML = '<img src="' + event.target.result + '" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateMobile()) {
                e.preventDefault();
                // focus mobile input so user can correct
                if (mobileInput) mobileInput.focus();
            }
        });
    }

    // live feedback while typing
    if (mobileInput) {
        mobileInput.addEventListener('input', function() {
            validateMobile();
        });
    }
});



//add new free slots--------------------
function addFreeSlot() {
    const day = document.getElementById('newSlotDay').value;
    const time = document.getElementById('newSlotTime').value.trim();
    const type = document.getElementById('newSlotType').value.trim();
    
    if (day && time && type) {
        const container = document.getElementById('freeSlotsContainer');
        const index = Date.now();
        
        const slotCard = document.createElement('div');
        slotCard.className = 'kal-slot-card';
        slotCard.innerHTML = `
            <button type="button" class="kal-slot-remove" onclick="removeSlot(this)">×</button>
            <div class="kal-slot-day">${day}</div>
            <div class="kal-slot-time">${time}</div>
            <div class="kal-slot-type">${type}</div>
            <input type="hidden" name="free_slots[${index}][id]" value="">
            <input type="hidden" name="free_slots[${index}][day]" value="${day}">
            <input type="hidden" name="free_slots[${index}][time]" value="${time}">
            <input type="hidden" name="free_slots[${index}][type]" value="${type}">
        `;
        container.appendChild(slotCard);
        
        // Clear form
        document.getElementById('newSlotTime').value = '';
        document.getElementById('newSlotType').value = '';
    }
}

//update free slots-----------------------
function editSlot(button) {

    const card = button.closest('.kal-slot-card');

    const dayDiv = card.querySelector('.kal-slot-day');
    const timeDiv = card.querySelector('.kal-slot-time');
    const typeDiv = card.querySelector('.kal-slot-type');

    const day = dayDiv.innerText;
    const time = timeDiv.innerText;
    const type = typeDiv.innerText;

    // Replace text with inputs
    dayDiv.innerHTML = `
        <select class="edit-day">
            <option ${day=="Monday"?"selected":""}>Monday</option>
            <option ${day=="Tuesday"?"selected":""}>Tuesday</option>
            <option ${day=="Wednesday"?"selected":""}>Wednesday</option>
            <option ${day=="Thursday"?"selected":""}>Thursday</option>
            <option ${day=="Friday"?"selected":""}>Friday</option>
            <option ${day=="Saturday"?"selected":""}>Saturday</option>
            <option ${day=="Sunday"?"selected":""}>Sunday</option>
        </select>
    `;

    timeDiv.innerHTML = `<input type="text" class="edit-time" value="${time}">`;

    typeDiv.innerHTML = `<input type="text" class="edit-type" value="${type}"> `;

    // Change button to Save
    button.innerText = "Save";
    button.onclick = function(){ saveSlot(this); };

}
//save slot after edit free slot
function saveSlot(button) {

    const card = button.closest('.kal-slot-card');

    const day = card.querySelector('.edit-day').value;
    const time = card.querySelector('.edit-time').value;
    const type = card.querySelector('.edit-type').value;

    // Update visible text
    card.querySelector('.kal-slot-day').innerText = day;
    card.querySelector('.kal-slot-time').innerText = time;
    card.querySelector('.kal-slot-type').innerText = type;

    // Update hidden inputs (IMPORTANT for DB update)
    // Keep the existing ID - do not modify it
    card.querySelector('input[name*="[day]"]').value = day;
    card.querySelector('input[name*="[time]"]').value = time;
    card.querySelector('input[name*="[type]"]').value = type;

    // Change button back to Edit
    button.innerText = "Edit";
    button.onclick = function(){ editSlot(this); };

}

//delete free slots----------
function removeSlot(button) {
    if(confirm('Are you sure you want to delete this slot?')){


        const removed_slot = button.closest('.kal-slot-card');
        const removed_id = removed_slot.querySelector('input[name*="[id]"]');

        if(removed_id && removed_id.value){
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'deleted_slots[]';
        input.value = removed_id.value;

        document.getElementById('deletedSlotsContainer').appendChild(input);
        }
        removed_slot.remove();

    } 

}
</script>